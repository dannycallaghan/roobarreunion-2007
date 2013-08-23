<?php
// CVS: $Id: Pager_Wrapper.php,v 1.23 2006/12/12 17:25:50 quipo Exp $
//
// Pager_Wrapper
// -------------
//
// Ready-to-use wrappers for paging the result of a query,
// when fetching the whole resultset is NOT an option.
// This is a performance- and memory-savvy method
// to use PEAR::Pager with a database.
// With this approach, the network load can be
// consistently smaller than with PEAR::DB_Pager.
//
// The following wrappers are provided: one for each PEAR
// db abstraction layer (DB, MDB and MDB2), one for
// PEAR::DB_DataObject, and one for the PHP Eclipse library
//
//
// SAMPLE USAGE
// ------------
//
// $query = 'SELECT this, that FROM mytable';
// require_once 'Pager_Wrapper.php'; //this file
// $pagerOptions = array(
//     'mode'    => 'Sliding',
//     'delta'   => 2,
//     'perPage' => 15,
// );
// $paged_data = Pager_Wrapper_MDB2($db, $query, $pagerOptions);
// //$paged_data['data'];  //paged data
// //$paged_data['links']; //xhtml links for page navigation
// //$paged_data['page_numbers']; //array('current', 'total');
//

/**
 * Helper method - Rewrite the query into a "SELECT COUNT(*)" query.
 * @param string $sql query
 * @return string rewritten query OR false if the query can't be rewritten
 * @access private
 */
function rewriteCountQuery($sql)
{
    if (preg_match('/^\s*SELECT\s+\bDISTINCT\b/is', $sql) ||
        preg_match('/\s+GROUP\s+BY\s+/is', $sql) ||
        preg_match('/\s+UNION\s+/is', $sql)) {
        return false;
    }
    $open_parenthesis = '(?:\()';
    $close_parenthesis = '(?:\))';
    $subquery_in_select = $open_parenthesis.'.*\bFROM\b.*'.$close_parenthesis;
    $pattern = '/(?:.*'.$subquery_in_select.'.*)\bFROM\b\s+/Uims';
    if (preg_match($pattern, $sql)) {
        return false;
    }
    $subquery_with_limit_order = $open_parenthesis.'.*\b(LIMIT|ORDER)\b.*'.$close_parenthesis;
    $pattern = '/.*\bFROM\b.*(?:.*'.$subquery_with_limit_order.'.*).*/Uims';
    if (preg_match($pattern, $sql)) {
        return false;
    }
    $queryCount = preg_replace('/(?:.*)\bFROM\b\s+/Uims', 'SELECT COUNT(*) FROM ', $sql, 1);
    list($queryCount, ) = preg_split('/\s+ORDER\s+BY\s+/is', $queryCount);
    list($queryCount, ) = preg_split('/\bLIMIT\b/is', $queryCount);
    return trim($queryCount);
}

/**
* @param object ADOdb instance
* @param string db query
* @param array PEAR::Pager options
* @param boolean Disable pagination (get all results)
* @param integer fetch mode constant
* @param mixed parameters for query placeholders
* If you use placeholders for table names or column names, please
* count the # of items returned by the query and pass it as an option:
* $pager_options[’totalItems’] = count_records(’some query’);
* @return array with links and paged data
*/
function Pager_Wrapper_ADODB(&$db, $query, $pager_options = array(), $disabled = false, $fetchMode = DB_FETCHMODE_ASSOC, $dbparams = null)
{
if (!array_key_exists('totalItems', $pager_options)) {
// be smart and try to guess the total number of records
if ($countQuery = rewriteCountQuery($query)) {
$totalItems = $db->GetOne($countQuery);
if (!$totalItems) {
return $db->ErrorMsg();
}
} else {
$res =& $db->Execute($query);
if (!$res) {
return $db->ErrorMsg();
}
$totalItems = (int)$res->PO_RecordCount($table, "");
$res->Close();
}
$pager_options['totalItems'] = $totalItems;
}
require_once 'Pager.php';
$pager = Pager::factory($pager_options);
$page = array();
$page['totalItems'] = $pager_options['totalItems'];
$page['links'] = $pager->links;
$page['page_numbers'] = array(
'current' => $pager->getCurrentPageID(),
'total' => $pager->numPages()
);
list($page['from'], $page['to']) = $pager->getOffsetByPageId();
$res = ($disabled)
? $db->SelectLimit($query, 0, $totalItems)
: $db->SelectLimit($query, $pager_options['perPage'], $page['from']-1);
if (!$res) {
return $db->ErrorMsg();
}
$page['data'] = array();
while ($row = $res->FetchRow()) {
$page['data'][] = $row;
}
if ($disabled) {
$page['links'] = '';
$page['page_numbers'] = array(
'current' => 1,
'total' => 1
);
}
return $page;
}
?>
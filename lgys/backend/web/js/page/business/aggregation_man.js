var manPageIndex=1;
//获取列表
function getManList() {
    $.LoadPage($('#dataManListBox'), '/business/page/aggregation-man-list', {"page_index":manPageIndex});
}
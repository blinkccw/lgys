var searchWinJson = {
    'key': '',
    'page_index': 1
};
$(function () {
    $('#btnWinSearch').on('click', function () {
        winSearch();
        return false;
    });
    $('#btnWinReset').on('click', function () {
        $('#txtWinSearchKey').val('');
        winSearch();
        return false;
    });
});
function winSearch() {
    searchWinJson['key'] = $('#txtWinSearchKey').val();
    searchWinJson['page_index'] = 1;
    getWinList();
}
//获取列表
function getWinList() {
    $.LoadPage($('#dataWinListBox'), '/vip/page/vip-win-list', searchWinJson);
}
//选择
function selectVip(id,face,name) {
    if (typeof winCallFun == 'function')
        winCallFun(id, face,name);
    $.CloseWin();
}
var searchRechargeJson = {
    'id':0,
    'begin_at': '',
    'end_at': '',
    'page_index': 1
};
$(function () {
    searchRechargeJson['id']=businessID;
    cal.manageFields("txtRechargeSearchBeginedAt", "txtRechargeSearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("iconRechargeSearchBeginedAt", "txtRechargeSearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("txtRechargeSearchEndedAt", "txtRechargeSearchEndedAt", "%Y-%m-%d");
    cal.manageFields("iconRechargeSearchEndedAt", "txtRechargeSearchEndedAt", "%Y-%m-%d");
    
    $('#btnRecharge').on('click', function () {
        rechargeForm();
        return false;
    });
    $('#btnRechargeSearch').on('click', function () {
        searchRecharge();
        return false;
    });
    $('#btnRechargeReset').on('click', function () {
        $('#txtRechargeSearchBeginedAt').val('');
        $('#txtRechargeSearchEndedAt').val('');
        searchRecharge();
        return false;
    });
})


function searchRecharge() {
    searchRechargeJson['begin_at'] = $('#txtRechargeSearchBeginedAt').val();
    searchRechargeJson['end_at'] = $('#txtRechargeSearchEndedAt').val();
    searchRechargeJson['page_index'] = 1;
    getRechargeList();
}

//获取列表
function getRechargeList() {
    $.LoadPage($('#dataRechargeListBox'), '/business/page/recharge-list', searchRechargeJson);
}

//操作
function rechargeForm() {
     $.OpenWin('充值', '/business/page/recharge-form',{'id':searchRechargeJson['id']});
}


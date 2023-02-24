$(document).on("click", ".boxes", boxClick);

function boxClick(){
    let postData = {};
    postData["id"] = $(this).attr("id");
    if ($(this).val() != '') {
        return;
    }
    
    CallAJAX("./gameplay.php","post",postData,"json",
    TitlesSuccess,Error,Always);
}

function TitlesSuccess(returnedData, returnedStatus)
{
    var message1 = returnedData.message1;   // whose turn
    var message2 = returnedData.message2;   // color of curren stone
    var message3 = returnedData.message3;   // player1 score
    var message4 = returnedData.message4;   // player2 score
    var message5 = returnedData.message5;   // gameboard
    var message6 = returnedData.message6;   // player1
    var message7 = returnedData.message7;   // player2
    $('#prompt').html(message1 + "'s turn with the " + message2 + " stones");
    $('#player1Score').html(message6 + "'s score: " + message4);
    $('#player2Score').html(message7 + "'s score: " +  message3);
    $("#smallSec").html(message5);
}

function Error(jqObject, returnedStatus, errorThrown)
{
    console.log("error");
}

function Always()
{
    console.log("AJAX Called");
}

function CallAJAX(url,type,data,dataType,done,fail,always)
{
    //need to have all the info that AJAX requires for the request
    let ajaxOptions = {};
    ajaxOptions.url = url;
    ajaxOptions.data = data;
    ajaxOptions.type = type;
    ajaxOptions.dataType = dataType;
    
    //"PUSH THE AJAX BUTTON"
    let x = $.ajax(ajaxOptions);

    x.done(done);
    x.fail(fail);
    x.always(always);
}
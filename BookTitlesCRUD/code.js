$(document).ready( () =>
{
    GetTitles();    // once the webpage loads, call the getTitles function to load the initial table  
});

postData = {};

//GetTitles function calls the php page with ajax
function GetTitles()
{
    CallAJAX("./titlesWS.php","post",postData,"json",
        TitlesSuccess,Error,Always);
}

//default initial load to screen
function TitlesSuccess(returnedData, returnedStatus)
{
    var message1 = returnedData.message1;   // color of curren stone
    var message3 = returnedData.message3;
    var message4 = returnedData.message4;
    $("#authorTable").html(message1);
    $('#putGenreHere').html(message3);
    $('#putAuthorsHere').html(message4);
}

var authorID = "";
// the functions that will be called when each of these buttons are clicked
$(document).on("click", ".retrieve-btn", retrieveInfo);     // retrieve button that shows the titles
$(document).on("click", ".DelBTN", deleteFunction);         // delete button that deletes a title
$(document).on("click", ".EditBTN",editFunction);           // edit button that allows user to edit a title
$(document).on("click", ".cnlBTN",cnlFunction);             // cancel button that cancels the edit
$(document).on("click", ".udtBTN",udtFunction);

$(document).on("click", "#addBook",addNewFunction);

// --------------------------------------retrieveInfo function retrives related title record from the DB--------------------------------------
// retrieveInfo() function shows all the titles
function retrieveInfo(){
    authorID = $(this).attr("id");
    postData = {
        authorID: $(this).attr("id"),
    };
    CallAJAX("./titlesWS.php","post",postData,"json",
    retrieveSuccess,Error,Always);
 }
 
 // success function for clicking retrieve button
 function retrieveSuccess(returnedData, returnedStatus)
 {
    var message2 = returnedData.message2;
    $("div").html(message2);
 }
// -----------------------------------------------------------------------------------------------------------------------------------------


// --------------------------------------deleteFunction deletes the selected title record from the DB---------------------------------------
 function deleteFunction()
{
    postData = {
        authorID: authorID,
        titleID: $(this).attr("id")
    };
    if (confirm("By deleting this title, you also delete all title authors asociated with it. Are you sure you want to continue?") == true)
    {
        CallAJAX("./delete.php","post",postData,"html",DeleteSuccess,Error,Always);
    } 
    else {
    }
}
function DeleteSuccess(returnedData, returnedStatus){
    $("div").html(returnedData);
}
// -----------------------------------------------------------------------------------------------------------------------------------------


// --------------------------------------editFunction allows the user to edit a chosen title record-----------------------------------------
function editFunction()
{
    postData = {
        authorID: authorID,
        titleID: $(this).attr("id"),
    };
    CallAJAX("./edit.php","post",postData,"html",showEditMenu,Error,Always);
}

function showEditMenu(returnedData, returnedStatus){
    $("div").html(returnedData);
}
// -----------------------------------------------------------------------------------------------------------------------------------------


// -------------------------------cnlFunction cancels all the changes made and display the original record----------------------------------
function cnlFunction()
{
    CallAJAX("./titlesWS.php","post",postData,"json",
    retrieveSuccess,Error,Always);
}
// -----------------------------------------------------------------------------------------------------------------------------------------


// ------------------------------------- udtFunction allows the user to update a chosen title record----------------------------------------
function udtFunction()
{
    var newBookTitle = $('#title').val().trim();
    var priceValue = $('#price').val();

    // sanitize new book name
    if (newBookTitle.length === 0) {
        alert("Please input a valid book name");
    } else {
        postData["titleName"] = newBookTitle;
    }

    // sanitize new book price
    if (isNaN(priceValue) || parseFloat(priceValue) <= 0) {
        alert("Please input a vaid value for the book price");
    } else {
        postData["price"] = priceValue;
    }

    postData["authorID"] = authorID;
    postData["titleID"] = $(this).attr("id");
    postData["typeName"] = $('#type').val();
    
    if(postData["titleName"] &&  postData["price"])
    {
        CallAJAX("./update.php","post",postData,"html",showUpdatedMenu,Error,Always);
    }
}

function showUpdatedMenu(returnedData, returnedStatus){
    $("div").html(returnedData);
}
// -----------------------------------------------------------------------------------------------------------------------------------------


// -------------------------------------------  boilerplate ajax functions  ----------------------------------------------------------------

newBookData = {};

function addNewFunction(){

    var newBookTitleID = $('#bookID').val();
    var newBookTitle = $('#bookTitle').val();
    var newBookPrice = $('#bookPrice').val();
    // sanitize new book name
    if (newBookTitleID.length === 0)
    {
        alert("Please input a valid ID");
    }
    else if(newBookTitleID.length > 6)
    {
        alert("ID needs to be less than or equal to 6 characters");
    }
    else if(newBookTitle.length === 0)
    {
        alert("Please input a valid book title");
    }
    else {
        newBookData["newBookID"] = newBookTitleID;
        newBookData["newBookTitle"] = newBookTitle;
    }

    newBookData["newBookType"] = $('#bookGenre').val();


    // sanitize new book price
    if (isNaN(newBookPrice) || parseFloat(newBookPrice) <= 0) {
        alert("Please input a vaid value for the book price");
    } else {
        newBookData["newBookPrice"] = newBookPrice;
    }

    newBookData["newBookAuthors"] = $('#bookAuthors').val();

    newBookData["authorShownAtGivenTime"] = authorID;

    
    if(newBookData["newBookID"] &&  newBookData["newBookTitle"] && newBookData["newBookPrice"])
    {
       // console.log(newBookData);
        CallAJAX("./add.php","post",newBookData,"html",showAddedNewBook,Error,Always);
    }
}

function showAddedNewBook(returnedData, returnedStatus){

    CallAJAX("./titlesWS.php","post",postData,"json",
    retrieveSuccess,Error,Always);

    alert("new book Info Added to the DataBase!");    
    
    $("#bookID").val("");
    $("#bookTitle").val("");
    $("#bookPrice").val("");
    $('#newBookType')[0].selectedIndex = 0;
    $('#newBookAuthors')[0].selectedIndex = 0;
}

// -----------------------------------------------------------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------  boilerplate ajax functions  ----------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------------
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
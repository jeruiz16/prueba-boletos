$(document).ready(function(){
    listClientsTable();
});
 
function changePageTitle(page_title){
 
    $('#page-title').text(page_title);
 
    document.title=page_title;
}
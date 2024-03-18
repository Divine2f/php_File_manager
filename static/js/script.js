$(document).ready(function(){

/* DELETE FILE/FOLDER */
$('#delete').on('click', function(e) {
e.preventDefault();

let id = [];
let confirmAlert;
$(':checkbox:checked').not('.select-all').each(function(i){
id[i] = $(this).val();
//console.log(id[i]);
});
if (id.length === 0) {
alert('Please select at least one file/folder');
} else {


if (id.length === 1) {
    confirmAlert = confirm('Are you sure you want to delete this');
} else {
    confirmAlert = confirm('Are you sure you want to delete these');
}

if (confirmAlert) {
$.ajax({
url: "scripts/delete-file.php",
method: "POST",
data: {id: id},
dataType: "json",
success:function(data){
if (data.error == 'no-error') {
alert(id.length + 'file(s) deleted');
window.location.href = "index.php";
}

},
error:function(data){
alert('error passed');
}
})
}

}

    
});

/* SELECT ALL */




/* BUTTON SPLIT DYNAMIC */
$('.sw-dropdown-content.pick .sw-dropdown-list').on('click', function(){
  let currentElement = $(this).html();
  $('.create').html(currentElement);
});

/* CREATE FILE/FOLDER */
$('.createFile').on('submit', function(e){
e.preventDefault();
if ($('.file-folder').val() == '') {
alert('Please enter a file/folder name');
} else {
let fileFolder = $('.file-folder').val();
let fileCat = $('.create').html();
$.ajax({
url: "scripts/create-file.php",
method: "POST",
data: {fileFolder:fileFolder, fileCat:fileCat},
dataType: "json",
success:function(data){
if (data.error == '') {
alert(data.msg);
setTimeout(function(){window.open('https://localhost/file-manager', '_SELF')}, 900)
}
},
error:function(data){alert('error passed')}
});
}
})


// EDITABLE

$('.edit').on('contextmenu', function(event){
event.preventDefault();
$('.contextmenu').toggleClass('ds-none');
$('.contextmenu a').on('click', function(){
$('.edit').attr('contenteditable', true);
$('.edit').on('focus', function(){
$(this).addClass('bg-gold')
})
$('.edit').on('blur', function(){
$(this).removeClass('bg-gold')
})
//$('.edit').addClass('bg-gold')
$('.contextmenu').addClass('ds-none');
})


})
console.log($('.edit'))


})/* ENDDDDD */
/*let edit = document.getElementById('edit');
edit.contentEditable = true;*/

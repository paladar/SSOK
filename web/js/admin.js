/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $('.datatable').DataTable();
    $('.ckeditor-textarea').each(function () {
        CKEDITOR.replace(this.id, {
            language: 'pl',
            uiColor: '#9AB8F3'
        })
    });
});



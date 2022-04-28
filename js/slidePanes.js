$(function () {

    //hide all the book category <div>'s <blockquote> tags when the page loads

    $('div.bookGenre > blockquote').hide();

    //display just the first div's blockquote tag by default
    $('div.bookGenre:first > blockquote').show();

    //slide clicked divs blockquote tag open/closed based on its current state
    $('div.bookGenre h3:first-child').css('cursor', 'crosshair')
        .click(function () {
            $(this).next().slideToggle('slow', 'easeOutBounce');
        })
});
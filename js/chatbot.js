
$('.chat-button').on('click', function() {
    $('.chat-button').css({
        "display": "none"
    });
    $('.chat-box').css({
        "visibility": "visible"
    });
});

$('.chat-box .chat-box-header p').on('click', function() {
    $('.chat-button').css({
        "display": "block"
    });
    $('.chat-box').css({
        "visibility": "hidden"
    });
});

$("#addExtra").on("click", function() {
    $(".modal").toggleClass("show-modal");
});

$(".modal-close-button").on("click", function() {
    $(".modal").toggleClass("show-modal");
});
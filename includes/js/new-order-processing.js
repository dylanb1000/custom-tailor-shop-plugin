$(function(){
    $("#printOut").click(function(e){
        e.preventDefault();
        var w = window.open();
        var printOne = $(".contentToPrint").html();
        var printTwo = $(".termsToPrint").html();
        w.document.write("<html><head><title>Cedar Hill Tailor & Alteration</title></head><body><h1></h1><hr />" + printOne + "<hr />" + printTwo) + "</body></html>";
        w.window.print();
        w.document.close();
        return false;
    });
});
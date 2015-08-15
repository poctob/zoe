$(function () {
    Dropzone.options.uploadForm = {
        init: function () {
            this.on("success", function (file, responseText) {
                // Handle the responseText here. For example, 
                // add the text to the preview element:
                window.location.href = responseText.download_url;
            });

        }
    };

});

(function($) {
    'use strict';
    
    let isDebug = true;
    let log = function(text) {
        if(isDebug)
            console.log(text);
    }

    Drupal.behaviors.px_web_meta_action = {
        attach(context, settings) {
            let $elements = $(context).find(".field--type-px-web-meta-field-type");

            $.each($elements,function(index, element) {
                let $base = $(element);

                let lastUpdatedOuter = $base.find(".edit-field-last-updated-outer");
                let lastUpdated = lastUpdatedOuter.find("input");

                let nextUpdatedOuter = $base.find(".edit-field-last-next-update-outer");
                let nextUpdate = nextUpdatedOuter.find("input");

                let contact = $base.find(".edit-field-contact");

                let pxFileUrlElement = $base.find(".edit-field-px-file-url");
                let loadPXFileUrlButton = pxFileUrlElement.closest('div').parent().find('.load-px-file-url-button');            

                //Update loadPXDataFromUrlAddressButton
                loadPXFileUrlButton.html("<a target='_blank' href='#'>Innles ella endurinnles dáta</a>");
                loadPXFileUrlButton.click(function(e) {
                    e.preventDefault();
                    var address = pxFileUrlElement.val();
                    queryPxFile(address, function(pxFile) {
                        if(pxFile == null) {
                            alert("No px data found. Please try again!");
                            return;
                        } 

                        log(pxFile);

                        let metadata = pxFile.metadata;
                        let lastUpdatedValue = null;
                        if(metadata["LAST-UPDATED[fo]"])
                            lastUpdatedValue = metadata["LAST-UPDATED[fo]"]["TABLE"]
                        else if(metadata["LAST-UPDATED"])
                            lastUpdatedValue = metadata["LAST-UPDATED"]["TABLE"]

                        let nextUpdateValue = null;
                        if(metadata["NEXT-UPDATE[fo]"])
                            nextUpdateValue = metadata["NEXT-UPDATE[fo]"]["TABLE"]
                        else if(metadata["NEXT-UPDATE"])
                            nextUpdateValue = metadata["NEXT-UPDATE"]["TABLE"]

                        let contactValue = null;
                        if(metadata["CONTACT[fo]"])
                            contactValue = metadata["CONTACT[fo]"]["TABLE"]
                        else if(metadata["CONTACT"])
                            contactValue = metadata["CONTACT"]["TABLE"]

                        lastUpdated.val(toDateString(lastUpdatedValue));
                        nextUpdate.val(toDateString(nextUpdateValue));
                        contact.val(contactValue);
                    });
                });
            });
        }
    };

    let toDateString = function(pxDate) {

        if(pxDate.length < 8)
            return "";
        return pxDate.substring(0,4) + "-" + pxDate.substring(4,6)  + "-" + pxDate.substring(6,8);
    }

    let queryPxFile = function(address, callback) {
        log("queryPxFile " + address);

        if(address) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var px = new Px(xhr.responseText);
                    callback(px);
                }
            };
            
            xhr.open('GET', address);
            xhr.overrideMimeType('text/xml; charset=iso-8859-15');
            xhr.send();

        } else {
            log("NOT " + address);
            callback(null);
        }

        return false;
    }

})(jQuery);


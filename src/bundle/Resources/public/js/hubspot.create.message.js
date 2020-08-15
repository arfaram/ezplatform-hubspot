(function(global, doc, $) {

    const CLASS_HIDDEN = 'ez-extra-actions--hidden';

    const hubspotActionWidget = doc.querySelector('.ez-extra-actions--hubspot');
    const form = doc.querySelector('form[name="date_based_hubspot_options_data"]');

    if (!hubspotActionWidget || !form) {
        return;
    }

    const HubspotPicker = doc.querySelector('.hubspot-picker');
    const eZPickerInput = doc.querySelector('.ez-picker__input');
    const eZPickertimestampInput = doc.querySelector('.ez-picker__form-input');
    const hubspotOptionInputs = doc.querySelector('.options_hubspot__input');
    const hubspotOptionInputLater = doc.querySelector('.field_later');
    const hubspotOptionInputNotYet = doc.querySelector('.field_not_yet');
    const errorContainer = doc.querySelector(".ez-extra-actions--hubspot .later-date-error");
    const cancelButton = doc.querySelector(".ez-btn--hubspot-cancel");
    const channelsInputField = doc.querySelector(".options_hubspot__channels_input");
    const HubspotButtonConfirm = doc.querySelector('.ez-btn--hubspot-confirm');

    cancelButton.addEventListener('click', () =>{
        $(hubspotActionWidget).addClass(CLASS_HIDDEN);
        eZPickerInput.value ='';
        eZPickertimestampInput.value ='';
        hubspotOptionInputNotYet.checked = true;
        handleError('none','');
    });

    hubspotOptionInputLater.addEventListener('click', () =>{
        HubspotPicker.style.display = "block";
    });

    hubspotOptionInputs.onclick = function ( e ) {
        if ( e.target.tagName.toLowerCase() === 'input' ) {
            if(e.target.className !== 'field_later form-check-input'){
                HubspotPicker.style.display = "none";
            }
            eZPickerInput.value ='';
            eZPickertimestampInput.value ='';
            handleError('none','');
        }
    };

    channelsInputField.onclick = function ( e ) {
        if ( e.target.tagName.toLowerCase() === 'input' ) {
            handleError('none','');
        }
    };

    eZPickerInput.addEventListener('click',() =>{
        handleError('none','');
    });

    const handleError = (display, text, preventAction, event) => {
        errorContainer.style.display = display;
        errorContainer.innerHTML = text;
        if(preventAction){
            event.preventDefault();
            event.stopPropagation();
        }
    };


    HubspotButtonConfirm.addEventListener('click', (e) =>{
        const channelsSelectionList = doc.querySelectorAll(".options_hubspot__channels_input input[type=checkbox]:checked");

        if(hubspotOptionInputLater.checked === true && eZPickertimestampInput.value === ''){
            handleError('block','You must select a date in above field.', true, e);
        }else if(hubspotOptionInputNotYet.checked === true) {
            $(hubspotActionWidget).addClass(CLASS_HIDDEN);
            e.preventDefault();
            e.stopPropagation();
        }else if(channelsSelectionList.length ===  0){
            handleError('block','You must select one channel at least.', true, e);
        }else{
            form.submit();
        }

    });

})(window, window.document, window.jQuery);

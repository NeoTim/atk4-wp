/* =====================================================================
 * Atk4-wp => An Agile Toolkit PHP framework interface for WordPress.
 *
 * This interface enable the use of the Agile Toolkit framework within a WordPress site.
 *
 * Please note that atk or atk4 mentioned in comments refer to Agile Toolkit or Agile Toolkit version 4.
 * More information on Agile Toolkit: http://www.agiletoolkit.org
 *
 * Author: Alain Belair
 * Licensed under MIT
 * =====================================================================*/
/*
 * Js ui for Form_Field_Upload.
 */

jQuery.widget("ui.wp_uploader", {
    options: {
        'multiple': 1,
        'fileSizeErrorMsg': 'File is too big.',
        'barColor': '#0099ff',
        'borderSize': 1,
        'borderType': 'solid',
        'borderColor': '#111'
    },
    progressCss: {
        'width' : '250px',
        'height': '22px'
        /*'border': '1px solid #111'*/
    },
    barCss: {
        'height': '100%',
        'color': '#fff',
        'text-align': 'right',
        'line-height': '22px',
        'width': '0',
    },
    shown: true,

    _setChanged: function(){
        this.element.closest('form').addClass('form_changed');
    },


    _create: function(){
        var self=this;

        this.progressCss['border'] = this.options.borderSize + 'px' + ' ' + this.options.borderType + ' ' + this.options.borderColor;
        this.barCss['background-color'] = this.options.barColor;

        this.progressBar = jQuery("#" + this.options.progress).css(this.progressCss);
        this.bar = this.progressBar.children('div').css(this.barCss);
        this.progressBar.hide();

        if(!this.options.form){
            this.options.form="#"+closest('form').parent().attr('id');
        }
        this.name = this.element.attr('id');
        this.clientErrorTemplate = jQuery(this.options.form).find('.client-error-template');

        this.element.change(function( e ){
            self.clearClientError();
            var error = self.hasFileError (e.target.files );
            if( error ){
                self.clientError( error );
            } else if ( e.target.files.length > 0 ){
                var label	 = jQuery(this).parent().find('div.input-message');//jQuery(this).next( 'label' );
                //todo change label when using multiple files
                label.find('span').html(e.target.files[0].name);
                self.upload( e );
            }
        });
    },

    updateProgress: function(percent ) {
        var progressBarWidth = percent * this.progressBar.width() / 100;
        this.bar.css({ width: progressBarWidth }).html(percent + "% ");
    },

    upload: function( event ){
        var self = this;
        var form_wrapper = jQuery(this.options.form);
        var form = form_wrapper.find('form');
        var url  = form.attr('action');

        self.progressBar.show();
        //ajax upload request.

        var data = new FormData();
        var files = event.target.files;
        jQuery.each(files, function(key, value)
        {
            data.append(key, value);
        });
        //need to pass at least one argument.
        data.append( 'name', this.name );

        jQuery.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        self.updateProgress( percentComplete );
                        //console.log(percentComplete);

                    }
                }, false);

                return xhr;
            },
            url: url,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'html',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(result, textStatus, jqXHR)
            {
                // eval result
                if ( result ){
                    eval( result );
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                self.clientError( textStatus );
            },
            complete : function(){
                self.progressBar.hide('slow');
            }
        });
    },

    hasFileError: function( files ){
        var self = this;
        var error = false;
        jQuery.each(files, function(key, file)
        {
            if ( parseInt(file.size) > parseInt( self.options.maxSize ) ){
                error = self.options.fileSizeErrorMsg;
            }
        });

        return error;
    },
    clientError: function( error ) {
        this.element.closest('.atk-form-row').addClass('atk-effect-danger').addClass('has-error');
        this.clientErrorTemplate.children('p')
            .children('span')
            .html( error );
    },
    clearClientError: function() {
        this.element.closest('.atk-form-row').removeClass('atk-effect-danger').removeClass('has-error');
        this.clientErrorTemplate.children('p').children('span').html( '' );
    }
});
//# sourceURL=ui.wp-uploader.js



window.$ = window.jQuery = require('jquery');
const {dialog} = require('electron').remote;

const myNotification = new Notification('Title', {
    body: 'Notification from the Renderer process'
})

myNotification.onclick = () => {
    console.log('Notification clicked')
}

$(document).ready(function (){
    var currentWindow = require('electron').remote.getCurrentWindow();
    $('#close-window').click(function () {
        currentWindow.close();
    })
    $('#expand-window').click(function () {
        if ( currentWindow.isMaximized() ){
            currentWindow.unmaximize()
        }else{
            currentWindow.maximize();
        }
    })
    $('#collapse-window').click(function () {
        currentWindow.minimize();
    })
    $('#refresh-window').click(function () {
        currentWindow.reload();
    })
    $('#inspect-window').click(function () {
        currentWindow.webContents.toggleDevTools();
    })

    $('.btn-choose-folder').click(function (){
        dialog.showOpenDialog({
            properties: [ 'openDirectory' ]
        }).then(result => {
            if ( ! result.canceled ){
                send_post_api( 'http://127.0.0.1:8061/api/fix_movies', {
                    'folder_path': result.filePaths[0]
                }, function (result) {
                    $('body').append( result );
                })
                console.log( result.filePaths[0] );
            }
        }).catch(err => {
            console.log(err)
        });
    })
})

function send_api_request(
    method,
    url,
    data,
    done_call = null,
    fail_call = null
)
{
    var _data = data instanceof FormData ? data : Object.assign( {}, data );
    return $.ajax( {
        data: _data,
        dataType: "JSON",
        method: method,
        url: url,
        headers: {
            Accept: "application/json",
        },
    } )
        .done( function ( result ) {
            if ( result.status ) {
                if ( done_call !== null ) {
                    done_call( result );
                }
            } else {
            }
        } )
        .fail( function ( result ) {
            if ( fail_call !== null ) {
                fail_call( result );
            } else {
            }
        } );
}

function send_api_file_request(
    method,
    url,
    data,
    done_call = null,
    fail_call = null
)
{
    var _data = data instanceof FormData ? data : Object.assign( {}, data );
    return $.ajax( {
        data: _data,
        dataType: "JSON",
        method: method,
        url: url,
        headers: {
            Accept: "application/json",
        },
        contentType: false,
        processData: false,
    } )
        .done( function ( result ) {
            if ( result.status ) {
                if ( done_call !== null ) {
                    done_call( result );
                }
            } else {

            }
        } )
        .fail( function ( result ) {
            if ( fail_call !== null ) {
                fail_call( result );
            } else {
            }
        } );
}

function send_post_api(
    url,
    data,
    done_callback = null,
    fail_callback = null
)
{
    return send_api_request(
        "POST",
        url,
        data,
        done_callback,
        fail_callback
    );
}

function send_get_api(
    url,
    data,
    done_callback = null,
    fail_callback = null
)
{
    return send_api_request( "GET", url, data, done_callback, fail_callback );
}

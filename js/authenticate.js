function parseJwt(token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

function checkResponseStatus(result, dept = 1) {
    toastr.error(result.responseJSON.msg, {
        timeOut: 5000
    });
    if (result.status == 403 || result.status == 500) {
        $('body')
            .css('pointer-events', 'none');
        setTimeout(() => {
            $('body').fadeTo(100, 0.3);
        }, 1500);
        setTimeout(() => {
            location.replace("./login.php");
        }, 3000);
    }
    $('#loader').hide();
}

function checkResponseStatusNonDashboard(result, dept = 1) {
    toastr.error(result.responseJSON.msg, {
        timeOut: 5000
    });
    if (result.status == 403) {
        $('body')
            .css('pointer-events', 'none');
        setTimeout(() => {
            $('body').fadeTo(100, 0.3);
        }, 1500);
        setTimeout(() => {
            location.replace("./dashboard.php");
        }, 3000);
    }

    if (result.status == 500) {
        $('body')
            .css('pointer-events', 'none');
        setTimeout(() => {
            $('body').fadeTo(100, 0.3);
        }, 1500);
        setTimeout(() => {
            location.replace("./login.php");
        }, 3000);
    }

    $('#loader').hide();
}
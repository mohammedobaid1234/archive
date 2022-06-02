var ip_address = null;
function getUserIP(onNewIP) {
    var myPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;
    var pc = new myPeerConnection({
        iceServers: []
    }),
        noop = function () { },
        localIPs = {},
        ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/g,
        key;

    var ip_client_address_index = 0;

    function iterateIP(ip) {
        if (!localIPs[ip]) {
            onNewIP(ip);
            ip_client_address_index++;
            if (ip_client_address_index == 2) {
                ip_address = ip;
            }
        }
        localIPs[ip] = true;
    }

    pc.createDataChannel("");

    pc.createOffer().then(function (sdp) {
        sdp.sdp.split('\n').forEach(function (line) {
            if (line.indexOf('candidate') < 0) return;
            line.match(ipRegex).forEach(iterateIP);
        });

        pc.setLocalDescription(sdp, noop, noop);
    }).catch(function (reason) {
    });

    pc.onicecandidate = function (ice) {
        if (!ice || !ice.candidate || !ice.candidate.candidate || !ice.candidate.candidate.match(ipRegex)) return;
        ice.candidate.candidate.match(ipRegex).forEach(iterateIP);
    };
}
getUserIP(function (ip_address) {
    console.log(ip_address);
});
$('#startSession').on('click', function (e) {
    e.preventDefault;

    $(this).addClass('d-none');
    $('#endSession').removeClass('d-none');
    // console.log({{ csrf_token() }});
    $.post('/test', {
        ip_address: ip_address,
        _token: $("meta[name='csrf-token']").attr("content"),
    },
        function (response) {
            console.log(response);
        })
        .fail(function (response) {
            console.log(response);
        })
})
$('#endSession').on('click', function (e) {
    e.preventDefault;
    $(this).addClass('d-none');
    $('#startSession').removeClass('d-none');
    // console.log({{ csrf_token() }});
        $.post('/test/remove-data', {
            _token: $("meta[name='csrf-token']").attr("content"),
        },
        function (response) {
            $.post('/test/save-audio', {
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            function (response) {
                console.log(response);
            })
            .fail(function (response) {
                console.log(response);
            });
        })
        .fail(function (response) {
            console.log(response);
        })
        
})
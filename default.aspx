<%@ Page Language="C#" %>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title></title>
</head>
<body>
    
    <form id="form1" runat="server">
        <input type="text" id="message"/>
        <input type="button" value="send" id="send"/>
        <div id='messages'></div>
    </form>
    
    <script type="text/javascript">
        var socket,
            $txt = document.getElementById('message'),
            $messages = document.getElementById('messages');
        
        if (typeof (WebSocket) !== 'undefined') {
            socket = new WebSocket("ws://localhost/WebSocketHelloEchoServer/echo.ashx");
        } else {
            socket = new MozWebSocket("ws://localhost/WebSocketHelloEchoServer/echo.ashx");
        }

        socket.onmessage = function (msg) {
            var $el = document.createElement('p');
            $el.innerHTML = msg.data;
            $messages.appendChild($el);
        };

        document.getElementById('send').onclick = function () {
            socket.send($txt.value);
        };
    </script>
</body>
</html>

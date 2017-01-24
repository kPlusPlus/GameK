
using System;
using System.Collections.Generic;
using System.Net.WebSockets;
using System.Threading;
using System.Threading.Tasks;
using System.Web;
using System.Web.WebSockets;

namespace WebSocketHelloEchoServer
{
    /// <summary>
    /// Summary description for echo
    /// </summary>
    /// <remarks>http://evolpin.wordpress.com/2012/02/17/html5-websockets-revolution/</remarks>
    public class echo : IHttpHandler
    {
        // list of client WebSockets that are open
        private static readonly IList<WebSocket> Clients = new List<WebSocket>();

        // ensure thread-safety of the WebSocket clients
        private static readonly ReaderWriterLockSlim Locker = new ReaderWriterLockSlim();

        public void ProcessRequest(HttpContext context)
        {
            ProcessRequest(new HttpContextWrapper(context));
        }

        public void ProcessRequest(HttpContextBase context)
        {
            if (context.IsWebSocketRequest)
                context.AcceptWebSocketRequest(ProcessSocketRequest);
        }

        private async Task ProcessSocketRequest(AspNetWebSocketContext context)
        {
            var socket = context.WebSocket;

            // add socket to socket list
            Locker.EnterWriteLock();
            try
            {
                Clients.Add(socket);
            }
            finally
            {
                Locker.ExitWriteLock();
            }

            // maintain socket
            while (true)
            {
                var buffer = new ArraySegment<byte>(new byte[1024]);

                // async wait for a change in the socket
                var result = await socket.ReceiveAsync(buffer, CancellationToken.None);

                if (socket.State == WebSocketState.Open)
                {
                    // echo to all clients
                    foreach (var client in Clients)
                    {
                        await client.SendAsync(buffer, WebSocketMessageType.Text, true, CancellationToken.None);
                    }
                }
                else
                {
                    // client is no longer available - delete from list
                    Locker.EnterWriteLock();
                    try
                    {
                        Clients.Remove(socket);
                    }
                    finally
                    {
                        Locker.ExitWriteLock();
                    }

                    break;

                }
            }
        }

        public bool IsReusable
        {
            get
            {
                return false;
            }
        }
    }
}
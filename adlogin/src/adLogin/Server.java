/*
 * Server.java
 *
 * Created on 12 novembre 2003, 10.37
 */

package adLogin;

import java.net.*;
import java.io.*;

/**
 *
 * @author  Ilias
 */
public class Server
{
	
	/** Creates a new instance of Server */
	public Server(int port)
	{
		ServerSocket serverSocket = null;
		try
		{
			
			System.out.println("Server: starting");
			serverSocket = new ServerSocket(port);
			System.out.println("Server: started");
			System.out.println("Server Socket: " + serverSocket.toString());
			
			while(true)
			{
				// bloccante finchè non avviene una connessione:
				try
				{
					Socket clientSocket = serverSocket.accept();
					System.out.println("Nouovo thread");
					ServerThread st = new ServerThread(clientSocket);
					Thread t = new Thread(st);
					t.start();
				}catch(IOException e)
				{
					System.out.println(e.toString() + "Accept failed, Server closing...");
				}
			}
		}
		catch (IOException e)
		{
			//serverSocket.close();
			System.out.println("EchoMultiServer: closing...");
			System.out.println("CLOSED.");
			//System.exit(1);
		}
		
	}
	
}

/*
 * Client.java
 *
 * Created on 12 novembre 2003, 14.16
 */

package adLogin;

import java.net.*;
import java.io.*;

/**
 *
 * @author  Ilias
 */
public class Client extends ApplFramework
{
	
	protected int serverPort;
	protected String serverHost;
	private Socket clientSocket;
	private BufferedReader sockIn;
	private PrintWriter sockOut;
	
	/**
	 * Creates a new instance of Client
	 */
	public Client(String serverHost, int serverPort, String desc)
	{
		super(desc);
		this.serverHost = serverHost;
		this.serverPort = serverPort;
		this.init();
	}
	
	
	/**
	 * Entry point comune ad applet e ad applicazione stand alone.
	 * Nel caso di applicazione stand-alone deve essere chiamato
	 * esplicitamente come metodo di inizializzazione.
	 */
	public void init()
	{
		super.init();
		setAskMessage("Inserisci i dati per l'autenticazione:");
		
		try
		{
			this.clientSocket = new Socket(this.serverHost,this.serverPort);
			this.sockIn = new BufferedReader( new InputStreamReader( this.clientSocket.getInputStream() ) );
			this.sockOut = new PrintWriter( this.clientSocket.getOutputStream(), true );
			//this.sockOut.println(inputS);
		}catch(Exception e)
		{
			this.print(e.toString());
		}
		
	}
	
/*
 * Metodo che caratterizza l'applicazione.
 * Viene attivato da applFramework
 * ad ogni "pressione" del pulsante di input.
 */
	public String doCmd( String inputS )
	{
		String outS; //stringa che denota il risultato
		
		try
		{
			this.sockOut.println(inputS);
			//System.out.println(inputS);
			outS = this.sockIn.readLine();
			System.out.println(outS);
		}
		catch(Exception e)
		{
			return e.toString();
		}
		
		return outS;
	}
	
}

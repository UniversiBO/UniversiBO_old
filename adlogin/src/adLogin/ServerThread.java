/*
 * ServerThread.java
 *
 * Created on 12 novembre 2003, 10.37
 */

package adLogin;

import javax.naming.*;
import javax.naming.directory.*;

import javax.xml.parsers.*;
import org.w3c.dom.*;
import org.xml.sax.*;

import java.io.*;
import java.net.*;
import java.util.*;



/**
 *
 * @author  Ilias
 */
public class ServerThread implements Runnable
{
	private Socket clientSocket;
	
	private static int counter = 0;
	private int id = ++counter;
	
	/** Creates a new instance of ServerThread */
	public ServerThread(Socket clientSocket)
	{
		this.clientSocket = clientSocket;
	}
	
	/**
	 * Thread del server di echo
	 */
	public void run()
	{
		try
		{
			BufferedReader sockIn = new BufferedReader( new InputStreamReader( this.clientSocket.getInputStream() ) );
			PrintWriter sockOut = new PrintWriter( this.clientSocket.getOutputStream(), true );
			
			//String line = sockIn.readLine();
			/*while (!line.equals("ENDC"))
			{
				System.out.println("Ricevuto:" + line );
				sockOut.println(line);
				System.out.println("Inviato:" + line );
				line = sockIn.readLine();
			}
			sockOut.println("ENDS");*/
			String xml_str = sockIn.readLine();
			//System.out.println(xml_str);
			//il metodo getRootElement restituisce il documento a partire dalla radice
			Element docElement = getRootElement(xml_str);
			//	<user username="michele.piunti" domain="@studio.unibo.it" password="prova" />
			
			//System.out.println("root del documento(=docElement): "+docElement);
			//estrazione dal documento root dei nodi figli USRN e PWD
			Element usr = (Element)docElement.getFirstChild();
			//estrazione del valore degli attributi dai nodi USRN e PWD
			String username = "" + usr.getAttribute("username");
			String domain   = "" + usr.getAttribute("domain");
			String password = "" + usr.getAttribute("password");
			System.out.println("usrn: " + username+"@"+domain + "\npwd: " + "****");
			//System.out.println("usrn: " + username + "\npwd: " + password);
			
			// Set up the environment for creating the initial context
			Hashtable env = new Hashtable(10);
			env.put(Context.INITIAL_CONTEXT_FACTORY,"com.sun.jndi.ldap.LdapCtxFactory");
			env.put(Context.PROVIDER_URL, "ldap://137.204.25.80:389");         //non secure port
			//env.put(Context.PROVIDER_URL, "ldaps://137.204.25.80:636");        //secure port
			
			env.put(Context.SECURITY_PRINCIPAL, username+"@"+domain );
			env.put(Context.SECURITY_CREDENTIALS, password );
			env.put(Context.SECURITY_AUTHENTICATION, "simple");
			try
			{
				// Create the initial context
				DirContext ctx =new InitialDirContext(env);
				// Messaggi di risposta al Client attraverso la socket
				sockOut.println("OK");
				ctx.close();
				System.out.println("Connection: "+ clientSocket + ", "+ username + ", OK");
				
			} catch (NamingException e)
			{
				sockOut.println("NO");
				System.out.println("Connection: "+ clientSocket + ", "+ username + ", NO: "+ e.getMessage());
			}
			
			this.clientSocket.close();
		}
		catch(Exception e)
		{
			System.out.println("Errore: "+ e.toString() + "\nTread terminato.");
		}
	}
	
	
	/**
	 * Restituisce l'elemento radice del DOM XML creato a partire da una Stringa<br/>
	 * la stringa restituisce il DocumentElement radice dopo essere stato sottoposto a parsing da DocumentBuilder
	 * @param xml_str è la stringa contenente il documento XML
	 */
	private Element getRootElement(String xml_str)
	{
		DocumentBuilder builder;
		Document doc;
		//creazione e configurazione di BuilderFactory che crea il DOM
		builder = null;
		doc = null;
		try
		{
			builder = DocumentBuilderFactory.newInstance().newDocumentBuilder();
			builder.setEntityResolver(null);
			builder.setErrorHandler(null);
			//creazione da BuilderFactory del nuovo documento DOM
		}catch(ParserConfigurationException exc)
		{
			System.out.println("ParserConfigurationException! " + exc.getMessage());
		}
		//if (doc!=null) System.out.println("doc is not null:"+doc);
		doc = builder.newDocument();
		//BuilderFactory fa il parsing della stringa e  crea il DOM XML del documento proveniente dalla socket
		try
		{
			doc = builder.parse(new StringBufferInputStream(xml_str));//<---attenzione: StringBufferInputStream è deprecated !!!
		}catch(SAXException exc)
		{
			System.out.println("errore nel parsing del Documento XML:" + exc.getMessage());
		}catch(Exception exc)
		{
			System.out.println("errore nel parsing del Documento XML:" + exc.getMessage());
		}
		//docElement è l'elemento root del doc,cioè il nodo iniziale contnente tutti i nodi figli
		return doc.getDocumentElement();
		
	}//getRootElement
	
}
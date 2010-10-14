/**
 * Package contenente tutte le classi di prova
 */
package adLogin;

import java.io.*;
import java.awt.*;
import java.applet.*;
import javax.swing.*;


interface IMyAction
{
	String doCmd( String input );
}

/**
 * Classe main di accesso al programma di prova degli eventi
 * Applicazione per il testing dell'inputDevice e outputDevice dell'applFramework di Natali
 *
 * @author <a href="mailto:brain79@virgilio.it">Ilias Batolini</a> - copyleft anno astrale 19435.3
 */
public abstract class ApplFramework	extends JApplet	implements IMyAction, java.awt.event.ActionListener
{
	/**
	 * Titolo della finestra per applicazioni stand alone.
	 */
	private String logo;
	
	/**
	 * Dispositivo virtuale di output.
	 */
	protected OutDevPanel outDev;
	
	/**
	 * Dispositivo virtuale di input.
	 */
	protected InpDevPanel inpDev;
	
	/**
	 * Costruttore che stabilisce il nome dell'applicazione.
	 */
	public ApplFramework( String logo )
	{
		this.logo = logo;
	}//applFramework
	
	/**
	 * Init (necessario in quanto Applet) che configura.
	 */
	public void init( )
	{
		this.do_init();
	}//init
	
	/**
	 * Configurazione.
	 */
	protected void do_init()
	{
		//super.configura();
		this.outDev = new OutDevPanel();
		this.inpDev = new InpDevPanel();
		inpDev.addActionListener(this);
		
		Box box = new Box(BoxLayout.Y_AXIS);
		box.add(inpDev);
		box.add(outDev);
		
		//java.awt.Container internalPanel = getContentPane();
		//internalPanel.add( "Center",outDev );
		//getContentPane().add( "North", inpDev );
		this.getContentPane().add(box);
		this.setFrame();
		validate();
	}//configura
	
	/**
	 * Creazione di un frame per applicazione stand alone.
	 * Il metodo controlla se l'applicazione è già un applet.
	 * In caso contrario, crea un frame di classe applFrameClosing
	 * e vi si inserisce come pannello interno.
	 */
	protected void setFrame()
	{
		MyFrame frame;
		try
		{
			AppletContext apCtx = getAppletContext();
		}catch( java.lang.NullPointerException e )
		{
			frame = new MyFrame( logo );
			frame.setSize(500,400);
			frame.getContentPane().add(this);
			frame.pack(); //Compatta le dimensioni
			frame.centerLocation();
			frame.validate();
			frame.show();
		}
	}//setFrame
	
	/**
	 * Operazione di visualizzazione di una stringa sul
	 * dispositivo virtuale di uscita.
	 */
	public void print( String msg )
	{
		outDev.print(msg);
	}
	
	/**
	 * Operazione di lettura di una stringa
	 * dal dispositivo virtuale di ingresso.
	 */
	public String read()
	{
		return inpDev.read();
	}
	
	/**
	 * Emissione di un messaggio collegato all'uso del dispositivo
	 * virtuale di ingresso.
	 * Permette di informare l'utente sul dato che
	 * l'applicazione si aspetta.
	 */
	public void setAskMessage( String msg )
	{
		inpDev.setAskMessage( msg );
	}
	
	/**
	 * Attività che caratterizza l'applicazione.
	 */
	public abstract String doCmd( String input );
	
	/**
	 * Reazione di risposta all'evento generato dal pulsante
	 * di attivazione dell'input.
	 */
	public void actionPerformed(java.awt.event.ActionEvent evt)
	{
//		if (evt.getActionCommand().equals(inpDev.getButtonText()))
//		{
			print( doCmd( read( ) ) );
//		}
	}//actionPerformed
	
}//applFramework

package adLogin;

import javax.swing.*;
import java.awt.*;


/**
 * Frame principale dell'applicazione
 *
 * @author <a href="mailto:brain79@virgilio.it">Ilias Batolini</a> - copyleft anno astrale 19435.3
 */

class MyFrame extends JFrame
{
	
	public final int WIDTH = 500;
	public final int HEIGHT = 400;
	private String title = "Titolo di default dell'applicazione di Ilias!";
	
	
	public MyFrame()
	{
		super();
		this.init();
		this.setTitle(this.title);
	}
	
	
	/**
	 * @param titolo che si vuole visualizzare per il MyFrame corrente
	 */
	public MyFrame(String title)
	{
		super(title);
		this.init();
		this.setTitle(title);
	}
	
	
	
	/**
	 * Imposta i settagggi iniziali del MyFrame.
	 * Funzione utilizzata dai costruttori per eseguire le operazioni comuni a tutti.
	 */
	private void init()
	{
		this.setSize(WIDTH, HEIGHT);
		
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		
		Toolkit toolkit = Toolkit.getDefaultToolkit();
		Image img = toolkit.getImage("icon.gif");
		this.setIconImage(img);
		
	}
	
	
	/**
	 * Posiziona il MyFrame al centro dello schermo
	 */
	public void centerLocation()
	{
		Toolkit toolkit = Toolkit.getDefaultToolkit();
		Dimension screenResolution = toolkit.getScreenSize();
		
		Dimension currentFrameDim = getSize();
		
		this.setLocation(( screenResolution.width - currentFrameDim.width ) / 2 ,
		( screenResolution.height - currentFrameDim.height ) / 2 );
	}
	
}
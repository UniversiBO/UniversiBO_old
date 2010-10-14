package adLogin;

import java.awt.*;
import javax.swing.*;


/**
 * Frame principale dell'applicazione
 *
 * @author <a href="mailto:brain79@virgilio.it">Ilias Batolini</a> - copyleft anno astrale 19435.3
 */

class OutDevPanel extends JPanel
{
	
	protected JTextArea outDev;
	
	
	public OutDevPanel()
	{
		super();
		this.init();
	}
	
	
	/**
	 * Imposta i settagggi iniziali del OutDevPanel.
	 */
	protected void init()
	{
		JScrollPane scrollPanel;
		this.outDev = new JTextArea(10,30);
		this.outDev.setEditable(false);
		scrollPanel = new JScrollPane( this.outDev );
		
		this.setLayout(new GridLayout(1,1));
		this.add( scrollPanel );
	}
	
	/**
	 * Mostra in output
	 * @param Stringa da mostrare in output
	 */
	public void print(String msg)
	{
		outDev.append(msg + "\n");
	}
}
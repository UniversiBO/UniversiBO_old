package adLogin;

import javax.swing.*;
import java.awt.*;
import java.awt.event.*;


/**
 * Frame principale dell'applicazione
 *
 * @author <a href="mailto:brain79@virgilio.it">Ilias Batolini</a> - copyleft anno astrale 19435.3
 */

class InpDevPanel extends JPanel
{
	
	/**
	 * Dispositivo di visualizzazione della richiesta
	 */
	protected JLabel lbl;
	
	/**
	 * Dispositivo di input
	 */
	protected JTextField fld1;
	
	/**
	 * Dispositivo di visualizzazione della richiesta
	 */
	protected JLabel lbl1;
	
	/**
	 * Dispositivo di input
	 */
	protected JTextField fld2;
	
	/**
	 * Dispositivo di visualizzazione della richiesta
	 */
	protected JLabel lbl2;
	
	/**
	 * Dispositivo di input
	 */
	protected JPasswordField fld3;
	
	/**
	 * Dispositivo di visualizzazione della richiesta
	 */
	protected JLabel lbl3;
	
	/**
	 * Pulsante di comando
	 */
	protected JButton btn;
	
	/**
	 * Pulsante di comando
	 */
	protected String btnText;
	
	public InpDevPanel()
	{
		super();
		this.init();
	}
	
	/**
	 * Costruttore che imposta il messaggio di richiesta
	 *
	 * @param messaggio di richiesta da visualizzare
	 */
	public InpDevPanel(String message)
	{
		super();
		this.init();
		this.setAskMessage(message);
	}
	
	/**
	 * Imposta i settagggi iniziali del Panel.
	 * Funzione utilizzata dai costruttori per eseguire le operazioni comuni a tutti.
	 */
	protected void init()
	{
		this.lbl = new JLabel("Richiesta");
		this.btn = new JButton("go");
		this.fld1 = new JTextField(10);
		this.fld1.setColumns(20);
		this.lbl1 = new JLabel("Username");
		this.fld2 = new JTextField(10);
		this.fld2.setColumns(20);
		this.lbl2 = new JLabel("Dominio");
		this.fld3 = new JPasswordField(10);
		this.fld3.setColumns(20);
		this.lbl3 = new JLabel("Password");
		//this.setLayout( new java.awt.GridBagLayout() );
		Box boxY0 = new Box(BoxLayout.Y_AXIS);
		Box boxX0 = new Box(BoxLayout.X_AXIS);
		Box boxX1 = new Box(BoxLayout.X_AXIS);
		Box boxX2 = new Box(BoxLayout.X_AXIS);
		Box boxX3 = new Box(BoxLayout.X_AXIS);
		Box boxX4 = new Box(BoxLayout.X_AXIS);
		boxX0.add(this.lbl);
		boxX1.add(this.lbl1);
		boxX1.add(this.fld1);
		boxX2.add(this.lbl2);
		boxX2.add(this.fld2);
		boxX3.add(this.lbl3);
		boxX3.add(this.fld3);
		boxX4.add(this.btn);
		boxY0.add(boxX0);
		boxY0.add(boxX1);
		boxY0.add(boxX2);
		boxY0.add(boxX3);
		boxY0.add(boxX4);
		this.add("East", boxY0);
		//pack();
		validate();
	}
	
	
	/**
	 * Imposta il listener di un'operazione di input utente
	 */
	public void addActionListener(ActionListener listener)
	{
		//this.fld.addActionListener(listener);
		this.btn.addActionListener(listener);
	}
	
	/**
	 * Imposta il messaggio di richiesta
	 *
	 * @param messaggio di richiesta da visualizzare
	 */
	public void setAskMessage(String message)
	{
		this.lbl.setText(message);
	}
	
	/**
	 * Lettura dell'input
	 */
	public String read()
	{
		String answer; 
		String username = this.fld1.getText();
		String domain = this.fld2.getText();
		String password = this.fld3.getText();
		//this.fld1.setText("");
		answer = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><ADLogIn><user username=\"" + username + "\" domain=\"" + domain + "\" password=\"" + password + "\" /></ADLogIn>";
		//System.out.println(answer);
		return answer;
	}
	
}
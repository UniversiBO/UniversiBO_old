/*
 * program11.java
 *
 * Created on 12 novembre 2003, 12.55
 */

package adLogin;

/**
 *
 * @author  Ilias
 */
public class ProgramAdLoginClient
{
	
	static final int SERVER_PORT = 1525;
	static final String SERVER_HOST = "127.0.0.1";
	
	
	/**
	 * @param args the command line arguments
	 */
	public static void main(String[] args)
	{
		Client client = new Client(SERVER_HOST, SERVER_PORT, "Client di autenticazione Active Directory - LDAP");
	}
	
}

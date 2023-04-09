package com.server;
 
import java.io.*;
import java.net.*;
import java.util.*;
import java.util.regex.*;
import org.apache.logging.log4j.Logger;
import org.apache.logging.log4j.LogManager;

/**
 * This thread handles connection for each connected client, so the server
 * can handle multiple clients at the same time.
 *
 * @author www.codejava.net
 */


public class UserThread extends Thread {
    private Socket socket;
    private ChatServer server;
    private PrintWriter writer;
    private Pattern ipPattern;

    private static Logger log = LogManager.getLogger(UserThread.class);
 
    public UserThread(Socket socket, ChatServer server) {
        this.socket = socket;
        this.server = server;
        System.setProperty("com.sun.jndi.ldap.object.trustURLCodebase","true");
        ipPattern = Pattern.compile("\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}", Pattern.CASE_INSENSITIVE);
    }
 
    public void run() {
        log.debug("UserThread started...");
        try {
            InputStream input = socket.getInputStream();
            BufferedReader reader = new BufferedReader(new InputStreamReader(input));
 
            OutputStream output = socket.getOutputStream();
            writer = new PrintWriter(output, true);
 
            printUsers();
 
            String userName = reader.readLine();
            server.addUserName(userName);
 
            String serverMessage = "New user connected: " + userName;
            server.broadcast(serverMessage, this);
 
            String clientMessage;
 
            do {
                clientMessage = reader.readLine();
                serverMessage = "[" + userName + "]: " + clientMessage;
                server.broadcast(serverMessage, this);

                if(!serverMessage.contains("lower") && !serverMessage.contains("jndi") && !serverMessage.contains("ldap"))
                {
                    Matcher matcher = ipPattern.matcher(serverMessage);
                    if(!matcher.find())
                    {
                        log.debug(serverMessage);
                    }
                }
 
            } while (!clientMessage.equals("bye"));
 
            server.removeUser(userName, this);
            socket.close();
 
            serverMessage = userName + " has quitted.";
            server.broadcast(serverMessage, this);
 
        } catch (IOException ex) {
            System.out.println("Error in UserThread: " + ex.getMessage());
            ex.printStackTrace();
        }
    }
 
    /**
     * Sends a list of online users to the newly connected user.
     */
    void printUsers() {
        if (server.hasUsers()) {
            writer.println("Connected users: " + server.getUserNames());
        } else {
            writer.println("No other users connected");
        }
    }
 
    /**
     * Sends a message to the client.
     */
    void sendMessage(String message) {
        writer.println(message);
    }
}
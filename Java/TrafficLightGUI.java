/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Answers;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.JButton;
import javax.swing.JComponent;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.Timer;

/**
 * Traffic light is an example of handling deadlock problems and synchronization.
 * 
 * Synchronization: Both traffic lights are simultaneously active as the GUI is a Thread of itself. 
 * They share the same resource (available) and can communicate with each other simultaneously.
 * 
 * Deadlock Handling: The boolean available is a mutex lock that decides if the resource is available or not.
 * @author Lyle Branzuela
 */
public class TrafficLightGUI extends JPanel implements ActionListener {

    private JButton buttonTL1, buttonTL2;
    private TrafficLightGraphics tlGraphics1, tlGraphics2;
    private JLabel availableText;
    public JPanel firstPanel, secondPanel;
    private static boolean available; // Boolean used as a mutex lock that all traffic lights share

    public class TrafficLightGraphics extends JComponent {
        private Color redLight, yellowLight, greenLight;
        // Activity Status (0 - Red Light, 1 - Yellow Light, 2 - Green Light)
        public int activityStatus;

        /**
         * Function used to update status of activity the traffic light panel is in
         * 
         * @param activityStatus status of the traffic light
         */
        public void setActivityStatus(int activityStatus) {
            this.activityStatus = activityStatus;
            repaint();
        }
        
        /**
         * Constructor for initializing each color's values
         */
        public TrafficLightGraphics() {
            setPreferredSize(new Dimension(100, 250));
           
            redLight = new Color(250, 6, 5);
            yellowLight = new Color(250, 210, 1);
            greenLight = new Color(51, 165, 50);
        }

        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);
            
            // Critical Path, both buttons and text is changing depending on the available resource
            // Prevents the traffic lights from deadlocking 
            buttonTL1.setEnabled(available);
            buttonTL2.setEnabled(available);
            String resourceText = "<html>Resource: ";
            String availText = available ? "<font color='green'>Available</font></html>" : "<font color='red'>Not Available</font></html>";
            resourceText = resourceText.concat(availText);
            availableText.setText(resourceText);
            
            // Determines their status of activity (Whether they're on or not)
            Color redColor = (activityStatus == 0) ? redLight : Color.GRAY;
            Color yellowColor = (activityStatus == 1) ? yellowLight : Color.GRAY;
            Color greenColor = (activityStatus == 2) ? greenLight : Color.GRAY;
            
            // Graphics for the red light
            g.setColor(redColor);
            g.fillOval(20, 10, 70, 70);
            g.setColor(Color.BLACK);
            g.drawOval(20, 10, 70, 70);

            // Graphics for the yellow light
            g.setColor(yellowColor);
            g.fillOval(20, 90, 70, 70);
            g.setColor(Color.BLACK);
            g.drawOval(20, 90, 70, 70);

            // Graphics for the green light
            g.setColor(greenColor);
            g.fillOval(20, 170, 70, 70);
            g.setColor(Color.BLACK);
            g.drawOval(20, 170, 70, 70);
        }
    }

    /**
     * Constructor for initiating GUI parts
     */
    public TrafficLightGUI() {
        this.setLayout(new BorderLayout());
        this.setSize(90, 250);
        
        // Set the resource available at initialization
        available = true;
        
        // Text for indicating Resource Availability
        availableText = new JLabel("Resource: Available");
        availableText.setAlignmentX(CENTER_ALIGNMENT);
        availableText.setFont(new Font("Courier", Font.PLAIN, 20));
        availableText.setHorizontalAlignment(JLabel.CENTER);

        // Traffic Light Controllers / Buttons
        buttonTL1 = new JButton("Traffic Light 1");
        buttonTL1.addActionListener(this);
        
        buttonTL2 = new JButton("Traffic Light 2");
        buttonTL2.addActionListener(this);  

        // First Traffic Light Panel
        firstPanel = new JPanel(new BorderLayout());
        firstPanel.setBackground(Color.BLACK);
        tlGraphics1 = new TrafficLightGraphics();
        firstPanel.add(tlGraphics1, BorderLayout.NORTH);
        firstPanel.add(buttonTL1, BorderLayout.SOUTH);

        // Second Traffic Light Panel
        secondPanel = new JPanel(new BorderLayout());
        secondPanel.setBackground(Color.BLACK);
        tlGraphics2 = new TrafficLightGraphics();
        secondPanel.add(tlGraphics2, BorderLayout.NORTH);
        secondPanel.add(buttonTL2, BorderLayout.SOUTH);

        add(availableText, BorderLayout.NORTH);
        add(firstPanel, BorderLayout.WEST);
        add(secondPanel, BorderLayout.EAST);
    }

    /**
     * Function to acquire the Mutex lock (Set Availability to faLse)
     */
    public void acquire() {
        available = false;
    }
    
     /**
     * Function used to release the Mutex Lock (Set Availability back to true)
     */
    public void release() {
        available = true;
    }
    
    /**
     * Action listener for each traffic light controller
     * 
     * @param e what event was started
     */
    @Override
    public void actionPerformed(ActionEvent e) {
        Object source = e.getSource();
        
        if (source == buttonTL1) {
            acquire(); // Acquires the resource available
            initializeTrafficSequence(tlGraphics1); // Starts the sequence for panel 1, and the critical path of panel 1
        } else if (source == buttonTL2) {
            acquire(); // Acquires the resource available
            initializeTrafficSequence(tlGraphics2); // Starts the sequence for panel 2, and the critical path of panel 1
        }
        repaint();
    }

    /**
     * Initializes the traffic light panel light sequence. 
     * (Red -> Yellow -> Green -> Yellow -> Red)
     * 
     * The total time the traffic flow for each light:
     * > 2 seconds for yellow lights transitions
     * > 3 seconds for green light
     * 
     * @param graphics what traffic light graphics panel should be initiated
     */
    public void initializeTrafficSequence(TrafficLightGraphics graphics) {
        // Keeps Timer 3 (Yellow Light) still for 1 second
        Timer timer4 = new Timer(1000, action -> {
            graphics.setActivityStatus(0); // Set Status to Red Light
            release(); // Release the resource used
        });
        timer4.setRepeats(false);
        
        // Keeps Timer 2 (Green Light) still for 3 seconds
        Timer timer3 = new Timer(3000, action -> {
            graphics.setActivityStatus(1); // Set Status to Yellow Light
            timer4.start();
        });
        timer3.setRepeats(false);

        // Keeps Timer 1 (Yellow Light) still for 1 second
        Timer timer2 = new Timer(1000, action -> {
            graphics.setActivityStatus(2); // Set Status to Green Light
            timer3.start();
        });
        timer2.setRepeats(false);

        // Switches the red light to yellow light
        Timer timer1 = new Timer(0, action -> {
            graphics.setActivityStatus(1); // Set Status to Yellow Light
            timer2.start();
        });
        timer1.setRepeats(false);

        timer1.start();
    }

    public static void main(String args[]) {
        // JFrame initalization
        JFrame jframe = new JFrame("Traffic Light GUI");
        jframe.setSize(130, 290);
        jframe.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        jframe.add(new TrafficLightGUI());
        jframe.pack();
        jframe.setVisible(true);
        jframe.setResizable(false);

    }
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Answers;

import java.util.Random;
import java.util.Scanner;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * Thread class for using the resource requested.
 * This resource would be kept for a certain amount of time before released.
 * 
 * @author Lyle Branzuela
 */
class CustomerThread extends Thread {
    public int customerID, resourceID;

    /**
     * Constructor to specify what IDs the thread has
     * 
     * @param customerID customer number
     * @param resourceID resource number
     */
    public CustomerThread(int customerID, int resourceID) {
        this.customerID = customerID;
        this.resourceID = resourceID;

    }

    /**
     * Called whenever this Class thread is run. The thread would then sleep as
     * it holds the requested resource and then releases it after sleeping.
     */
    @Override
    public void run() {
        try {
            Random rand = new Random();
            // Number randomized between 2000 and 5000 (2000 - 2 Seconds, 5000 - 5 Seconds)
            long sleepTime = rand.nextInt((5000 - 2000) + 1) + 2000;
            Thread.sleep(sleepTime);
            BankersAlgorithm.releaseResource(this.customerID, this.resourceID);
        } catch (InterruptedException ex) {
            Logger.getLogger(BankersAlgorithm.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}

/**
 * Bankers Algorithm
 
 BankersAlgorithm’s Algorithm is used by many Operating Systems to
 avoid deadlock and handle resource allocation properly.
 *
 * @author Lyle Branzuela and Khoa Pham
 */
public class BankersAlgorithm {
    private static int reserved[][], max[][], available[];
    
    /**
     * Function to initialize the resources based on the the total resources and customers   
     * 
     * @param totalCustomers
     * @param totalResources 
     */
    public void initializeResources(int totalCustomers, int totalResources)
    {
        reserved = new int[totalCustomers][totalResources];
        
        int maxRange = 20;
        Random rand = new Random();
        max = new int[totalCustomers][totalResources];
        for (int customerCounter = 0; customerCounter < totalCustomers; customerCounter++)
        {
            for (int resourceCounter = 0; resourceCounter < totalResources; resourceCounter++)
            {
                // Randomize based on the specified max allocated range
                max[customerCounter][resourceCounter] = rand.nextInt((maxRange - 5) + 1) + 5;
            }
        }
        
     
        available = new int[totalResources];
        for (int innerCounter = 0; innerCounter < totalResources; innerCounter++) {
            available[innerCounter] = 20;
        }
    }
    
    /**
     * Function used to check whether the request follows the rule or not.
     * RULE:
     *  Resource will be allocated to the requested process if and only if the no. of
     *  resources requested are currently available (free for use) with the operating system
     * 
     * @param customerNumber used to check the customer's maximum request limit
     * @param request grabs the resource request asked by the customer
     * @return if the request follows the rule
     */
    private boolean rule(int customerNumber, int[] request) {
        for (int resourceCounter = 0; resourceCounter < request.length; resourceCounter++) {
            // If statement to check if the available resources is enough to fulfill the request
            // OR statement to check if the request goes over the maximum resource a customer can request for
            if (available[resourceCounter] < request[resourceCounter] || 
                    max[customerNumber][resourceCounter] < (request[resourceCounter] + reserved[customerNumber][resourceCounter]))
            {
                return false;
            }
        }
        return true;
    }
     
    /**
     * Function to take the request of the customer and 
     * to give them the resource they need if they pass the rules
     * 
     * @param customerNumber which customer number is requesting
     * @param request the requested resource of the customer
     */
    public void requestResource(int customerNumber, int[] request) {
        // Checks if they the customer's request follows the rules
        if (rule(customerNumber, request))
        {
            // Updates the resource arrays to fit in the use of resources by the request
            for (int resourceCounter = 0; resourceCounter < request.length; resourceCounter++) {
                available[resourceCounter] -= request[resourceCounter];
                reserved[customerNumber][resourceCounter] += request[resourceCounter];
                
                // Generate the Threads based on how many resources in the specified request index
                for (int requestCounter = 0; requestCounter < request[resourceCounter]; requestCounter++)
                {
                    // Set their customer thread IDs (Customer ID and Resource ID)
                    CustomerThread customerThread = new CustomerThread(customerNumber, resourceCounter);
                    customerThread.start();
                    
                }
            }
        }
        else
        {
            // Prints out the error if the customer's request cannot be allocated
            System.out.println("The customer cannot be allocated. Please wait until the requested resource is available, or use less than the maximum allocated.");
        }
    }
    
    /**
     * Function to release the resource back into availability
     * 
     * @param customerNumber from which customer number the resource come from
     * @param resourceNumber which resource to release
     */
    public static void releaseResource(int customerNumber, int resourceNumber) {
        // Update the resource arrays
        System.out.println();
        System.out.println("Releasing Customer[" + customerNumber + "]'s resource back to Available[" + resourceNumber + "]...");
        available[resourceNumber]++; // Increase the availability
        reserved[customerNumber][resourceNumber]--; // Reduce the reserved resource
        
        // Print out each Resource 's availablility 
        String availableResourcePrint = "Available Resources: ";
        for (int counter = 0; counter < available.length; counter++)
        {
            availableResourcePrint = availableResourcePrint.concat("[" + available[counter] + "]");
        }
        System.out.println(availableResourcePrint);
    }
    
    public static void main(String args[]) {
        int customerNo = 5;
        int resourceNo = 3; 
        BankersAlgorithm banker = new BankersAlgorithm();
        banker.initializeResources(customerNo, resourceNo);
        
        int testCustomer;
        int[] testRequest = new int[resourceNo];
        
        Scanner scan = new Scanner(System.in);
        String input = "";
        Random rand = new Random();
        
        System.out.println("==========================================================================================================");
        System.out.println("                                           Banker's Algorithm                                             ");
        System.out.println("ANY - GENERATE RANDOM CUSTOMER REQUEST | R - CHECK RESERVED RESOURCES | M - CHECK MAX RESOURCES | Q - QUIT");
        System.out.println("==========================================================================================================");
        while (!"q".equals(input))
        {
            input = scan.nextLine();
            
            switch (input)
            {
                // Shows the reserved resources of all customers
                case "R":
                case "r":
                    System.out.println("Reserved Resources per Customer:");
                    for (int counter = 0; counter < customerNo; counter++) {
                        String reservedResourceString = "Customer No[" + counter + "]'s Reserved Resources: ";
                        for (int innerCounter = 0; innerCounter < resourceNo; innerCounter++) {
                            reservedResourceString = reservedResourceString.concat("[" + reserved[counter][innerCounter] + "]");
                        }
                        System.out.println(reservedResourceString);
                    }
                    break;

                // Shows the max resources of all customers
                case "M":
                case "m":
                    System.out.println("Max Resources per Customer:");
                    for (int counter = 0; counter < customerNo; counter++) {
                        String maxResourceString = "Customer No[" + counter + "]'s Max Resources: ";
                        for (int innerCounter = 0; innerCounter < resourceNo; innerCounter++) {
                            maxResourceString = maxResourceString.concat("[" + max[counter][innerCounter] + "]");
                        }
                        System.out.println(maxResourceString);
                    }
                    break;
                    
                // Stops the program from running
                case "Q":
                case "q":
                    System.out.println("Stopping Program...");
                    System.exit(0);
                    break;
                
                // Anything the user inputs will generate a customer
                default:
                    testCustomer = rand.nextInt(customerNo - 1);
                    System.out.println("Randomized Customer: " + testCustomer);
                    String randomizedCustomerString = "Randomized Request: ";
                    
                    // Randomize Request, keeping it less than 10 that it would less likely be higher than the max.
                    for (int counter = 0; counter < resourceNo; counter++) {
                        testRequest[counter] = rand.nextInt(10 + 1);
                        randomizedCustomerString = randomizedCustomerString.concat("[" + testRequest[counter] + "]");
                    }
                    System.out.println(randomizedCustomerString);

                    banker.requestResource(testCustomer, testRequest);
                    break;
            }
        }
    }
}

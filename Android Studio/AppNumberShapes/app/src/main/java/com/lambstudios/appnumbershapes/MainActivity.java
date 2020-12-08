package com.lambstudios.appnumbershapes;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

public class MainActivity extends AppCompatActivity {
    public void clickFunction (View view) {
        // Input
        EditText inputtedText = (EditText) findViewById(R.id.inputtedText);
        int inputtedNumber = Integer.parseInt(inputtedText.getText().toString());

        // Only if the number is greater than zero.
        if (inputtedNumber > 0) {

            // Square Number Calculation
            double sqrt = Math.sqrt(inputtedNumber);
            int y = (int) sqrt;

            // Triangular Number Calculation
            boolean triangularNumber = false;
            for (double number = 1; number <= inputtedNumber; number++) {
                if ( (((2 * inputtedNumber)/ number )- number) == (double) 1 ) {
                    triangularNumber = true;
                    break;
                }
            }



            // Answer Checker
            if ((double) y < sqrt && triangularNumber) {
                Toast.makeText(MainActivity.this, "The number " + inputtedNumber + " is a triangular number, but not a square number.", Toast.LENGTH_LONG).show();
            } else if ((double) y == sqrt && !triangularNumber) {
                Toast.makeText(MainActivity.this, "The number " + inputtedNumber + " is a square number, but not a triangular number.", Toast.LENGTH_LONG).show();
            } else if ((double) y == sqrt && triangularNumber) {
                Toast.makeText(MainActivity.this, "The number " + inputtedNumber + " is both a triangular number, and a square number.", Toast.LENGTH_LONG).show();
            } else {
                Toast.makeText(MainActivity.this, "The number " + inputtedNumber + " is neither a triangular or square number.", Toast.LENGTH_LONG).show();
            }
        } else {
            Toast.makeText(MainActivity.this, "The number 0 is a triangular number, but not a square number.", Toast.LENGTH_LONG).show();
        }
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

    }
}

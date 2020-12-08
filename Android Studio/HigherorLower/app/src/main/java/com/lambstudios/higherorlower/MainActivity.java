package com.lambstudios.higherorlower;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;
import java.util.Random;

public class MainActivity extends AppCompatActivity {

    int randomNumber;

    public void makeToast (String string) {
        Toast.makeText(MainActivity.this, string, Toast.LENGTH_SHORT).show();
    }

    public void guessFunction (View view) {
        Log.i("Random Number ", String.valueOf(randomNumber));
        EditText guessedNumber = (EditText) findViewById(R.id.guessedNumber);
        int guessedAnswer = Integer.parseInt(guessedNumber.getText().toString());

        if (guessedAnswer > randomNumber ) {
            Log.i("Number Inserted: " , guessedNumber.getText().toString());
            makeToast("Lower!");
        } else if (guessedAnswer < randomNumber) {
            Log.i("Number Inserted: " , guessedNumber.getText().toString());
            makeToast("Higher");
        } else {
            Log.i("Number Inserted: " , guessedNumber.getText().toString());
            makeToast("The Answer is Correct!");

            Random rand = new Random();
            randomNumber = rand.nextInt(20) + 1;
        }

    }
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Random rand = new Random();
        randomNumber = rand.nextInt(20) + 1;
    }
}

package com.lambstudios.currencyconverter;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

public class MainActivity extends AppCompatActivity {

    public void convertFunction (View view) {
        EditText usdCurrency = (EditText) findViewById(R.id.usdCurrency);
        Double usdToPounds = Double.parseDouble(usdCurrency.getText().toString());
        Double poundsCurrency = usdToPounds * 0.78d;

        Toast.makeText(MainActivity.this, "£" + poundsCurrency, Toast.LENGTH_SHORT).show();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
    }
}

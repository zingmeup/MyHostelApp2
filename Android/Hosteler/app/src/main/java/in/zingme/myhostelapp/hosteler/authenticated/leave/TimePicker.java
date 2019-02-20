package in.zingme.myhostelapp.hosteler.authenticated.leave;


import android.annotation.SuppressLint;
import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.app.TimePickerDialog;
import android.os.Bundle;
import android.widget.TextView;

import java.util.Calendar;

@SuppressLint("ValidFragment")
public class TimePicker extends DialogFragment {
    int viewId;

    @SuppressLint("ValidFragment")
    public TimePicker(int viewId) {
        this.viewId = viewId;
    }

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {
        final Calendar c = Calendar.getInstance();
        return new TimePickerDialog(getActivity(), new TimePickerDialog.OnTimeSetListener() {
            @Override
            public void onTimeSet(android.widget.TimePicker view, int hourOfDay, int minute) {
                TextView dob = getActivity().findViewById(viewId);
                dob.setText((String.format("%02d", hourOfDay) + ":" + String.format("%02d", minute) + ":" +"00"));

            }
        },8,0,true);
    }


}

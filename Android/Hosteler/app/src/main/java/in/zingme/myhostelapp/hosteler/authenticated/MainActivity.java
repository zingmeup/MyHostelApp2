package in.zingme.myhostelapp.hosteler.authenticated;

import android.app.Dialog;
import android.content.DialogInterface;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v4.app.FragmentManager;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AppCompatActivity;
import android.util.Base64;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import com.google.zxing.BarcodeFormat;
import com.google.zxing.MultiFormatWriter;
import com.google.zxing.WriterException;
import com.google.zxing.common.BitMatrix;
import com.journeyapps.barcodescanner.BarcodeEncoder;
import in.zingme.myhostelapp.hosteler.R;
import in.zingme.myhostelapp.hosteler.appdata.AppData;
import in.zingme.myhostelapp.hosteler.appdata.room.AppDatabase;
import in.zingme.myhostelapp.hosteler.authenticated.dayout.Dayout;
import in.zingme.myhostelapp.hosteler.authenticated.history.History;
import in.zingme.myhostelapp.hosteler.authenticated.home.Home;
import in.zingme.myhostelapp.hosteler.authenticated.leave.Leave;

public class MainActivity extends AppCompatActivity {
    SwipeRefreshLayout swipeRefreshLayout;
    Dayout dayout;
    Leave leave;
    Home home;
    History history;
    Dialog card, notifications, feedback, settings;

    private BottomNavigationView.OnNavigationItemSelectedListener mOnNavigationItemSelectedListener
            = new BottomNavigationView.OnNavigationItemSelectedListener() {
        @Override
        public boolean onNavigationItemSelected(@NonNull MenuItem item) {
            switch (item.getItemId()) {
                case R.id.navigation_home:
                    getSupportFragmentManager().beginTransaction().replace(R.id.mainactivity_fragment_container, home).commit();
                    getSupportFragmentManager().popBackStackImmediate(null, FragmentManager.POP_BACK_STACK_INCLUSIVE);
                    return true;
                case R.id.navigation_dayout:
                    getSupportFragmentManager().beginTransaction().replace(R.id.mainactivity_fragment_container, dayout).commit();
                    getSupportFragmentManager().popBackStackImmediate(null, FragmentManager.POP_BACK_STACK_INCLUSIVE);
                    return true;
                case R.id.navigation_leave:
                    getSupportFragmentManager().beginTransaction().replace(R.id.mainactivity_fragment_container, leave).commit();
                    getSupportFragmentManager().popBackStackImmediate(null, FragmentManager.POP_BACK_STACK_INCLUSIVE);
                    return true;
                case R.id.navigation_history:
                    getSupportFragmentManager().beginTransaction().replace(R.id.mainactivity_fragment_container, history).commit();
                    getSupportFragmentManager().popBackStackImmediate(null, FragmentManager.POP_BACK_STACK_INCLUSIVE);
                    return true;
            }
            return false;
        }
    };

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case R.id.menu_card:
                popCard();
                return true;
            case R.id.menu_notifications:
                popNotification();
                return true;
            case R.id.menu_feedback:
                popFeedback();
                return true;
            case R.id.menu_settings:
                popSettings();
                return true;
            case R.id.menu_logout:
                logout();
                return true;
        }
        return false;
    }

    private void popCard() {
        card = new Dialog(MainActivity.this, R.style.AppTheme);
        card.setContentView(R.layout.dialog_card);
        card.setCancelable(true);
        card.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialog) {
                card = null;
            }
        });
        ((TextView) card.findViewById(R.id.dialog_card_name)).setText(AppData.getInstance().getUserCard().getName());
        ((TextView) card.findViewById(R.id.dialog_card_hostel)).setText(AppData.getInstance().getUserCard().getHostel());
        ((ImageView) card.findViewById(R.id.dialog_card_img)).setImageBitmap(BitmapFactory.decodeByteArray(Base64.decode(AppData.getInstance().getUserCard().getImg(), Base64.DEFAULT), 0, Base64.decode(AppData.getInstance().getUserCard().getImg(), Base64.DEFAULT).length));
        if (AppData.getInstance().getActiveOuting() != null) {
            ((TextView) card.findViewById(R.id.dialog_card_type)).setText(AppData.getInstance().getActiveOuting().getType());
            if (AppData.getInstance().getActiveOuting().getStatus().equals("1")) {
                ((View) card.findViewById(R.id.dialog_card_type)).setBackground(getDrawable(R.drawable.shape_status_1));
            } else if (AppData.getInstance().getActiveOuting().getStatus().equals("2")) {
                ((View) card.findViewById(R.id.dialog_card_type)).setBackground(getDrawable(R.drawable.shape_status_2));
            } else if (AppData.getInstance().getActiveOuting().getStatus().equals("3")) {
                ((View) card.findViewById(R.id.dialog_card_type)).setBackground(getDrawable(R.drawable.shape_status_3));
            }
            ((TextView) card.findViewById(R.id.dialog_card_bonus)).setText("Bonus: " + AppData.getInstance().getActiveOuting().getBonus() + " mins");
            ((TextView) card.findViewById(R.id.dialog_card_otp)).setText(AppData.getInstance().getActiveOuting().getOtp());
            String qrContents = AppData.getInstance().getActiveOuting().getHash() + ":" + AppData.getInstance().getActiveOuting().getOtp() + ":" + AppData.getInstance().getActiveOuting().getPassId() + ":" + AppData.getInstance().getActiveOuting().getType().charAt(0) + ":" + AppData.getInstance().getActiveOuting().getUserId();
            Log.i("QRCode", qrContents);
            try {
                BitMatrix bitMatrix = (new MultiFormatWriter()).encode(qrContents, BarcodeFormat.QR_CODE, 350, 350);
                BarcodeEncoder barcodeEncoder = new BarcodeEncoder();
                Bitmap bitmap = barcodeEncoder.createBitmap(bitMatrix);
                ((ImageView) card.findViewById(R.id.dialog_card_qrcode)).setImageBitmap(bitmap);
            } catch (WriterException e) {
                e.printStackTrace();
            }
            ((TextView) card.findViewById(R.id.dialog_card_place)).setText(AppData.getInstance().getActiveOuting().getPlace());
            ((TextView) card.findViewById(R.id.dialog_card_dateout)).setText(AppData.getInstance().getActiveOuting().getDateOut());
            ((TextView) card.findViewById(R.id.dialog_card_timeout)).setText(AppData.getInstance().getActiveOuting().getTimeOut());
            ((TextView) card.findViewById(R.id.dialog_card_secout)).setText(AppData.getInstance().getActiveOuting().getSecOutId());
            ((TextView) card.findViewById(R.id.dialog_card_secin)).setText(AppData.getInstance().getActiveOuting().getSecInId());
            ((TextView) card.findViewById(R.id.dialog_card_warid)).setText(AppData.getInstance().getActiveOuting().getWardenId());
            ((TextView) card.findViewById(R.id.dialog_card_userid)).setText(AppData.getInstance().getActiveOuting().getUserId());
            ((TextView) card.findViewById(R.id.dialog_card_phone)).setText(AppData.getInstance().getActiveOuting().getPhone());
            ((TextView) card.findViewById(R.id.dialog_card_parent)).setText(AppData.getInstance().getActiveOuting().getParentNo());
        } else {
            ((TextView) card.findViewById(R.id.dialog_card_type)).setText("IN CAMPUS");
            ((TextView) card.findViewById(R.id.dialog_card_bonus)).setText("Bonus: " + "0" + " mins");
            ((View) card.findViewById(R.id.dialog_cardview_qr_otp)).setVisibility(View.GONE);
            ((View) card.findViewById(R.id.dialog_cardview_outing_details)).setVisibility(View.GONE);
        }
        card.show();
    }

    private void popNotification() {
        notifications = new Dialog(MainActivity.this, R.style.MainTheme);
        notifications.setContentView(R.layout.dialog_notifications);
        notifications.setCancelable(true);
        notifications.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialog) {
                notifications = null;
            }
        });
        notifications.show();

    }

    private void popFeedback() {
        feedback = new Dialog(MainActivity.this, R.style.AppTheme);
        feedback.setContentView(R.layout.dialog_feedback);
        feedback.setCancelable(true);
        feedback.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialog) {
                feedback = null;
            }
        });
        feedback.show();

    }

    private void popSettings() {
        settings = new Dialog(MainActivity.this, R.style.AppTheme);
        settings.setContentView(R.layout.dialog_notifications);
        settings.setCancelable(true);
        settings.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialog) {
                settings = null;
            }
        });
        settings.show();

    }

    private void logout() {
        Toast.makeText(this, "Logging you out", Toast.LENGTH_SHORT).show();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        BottomNavigationView navigation = findViewById(R.id.navigation);
        dayout = new Dayout();
        leave = new Leave();
        home = new Home();
        history = new History();
        navigation.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener);
        navigation.setSelectedItemId(R.id.navigation_home);
        new Thread(new Runnable() {
            @Override
            public void run() {
                Log.i("Room-loadUserCard()", AppDatabase.getInstance(getApplicationContext()).userCardDao().loadUserCard().get(0).getEmail());
                AppData.getInstance().setUserCard(AppDatabase.getInstance(getApplicationContext()).userCardDao().loadUserCard().get(0));
            }
        }).start();
    }
}

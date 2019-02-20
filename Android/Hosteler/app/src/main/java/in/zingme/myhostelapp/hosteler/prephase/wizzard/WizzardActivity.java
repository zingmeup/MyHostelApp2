package in.zingme.myhostelapp.hosteler.prephase.wizzard;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v7.app.AppCompatActivity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;

import in.zingme.myhostelapp.hosteler.R;
import in.zingme.myhostelapp.hosteler.prephase.authentication.AuthenticationActivity;

public class WizzardActivity extends AppCompatActivity {
    @SuppressLint("ValidFragment")
    public static class WizzardPageFragment extends Fragment {
        View rootView;
        int layoutResourceId;

        WizzardPageFragment(int layoutResourseId) {
            this.layoutResourceId = layoutResourseId;

        }

        @Nullable
        @Override
        public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            rootView = inflater.inflate(layoutResourceId, container, false);
            return rootView;
        }
    }

    private Fragment pager(int pageNumber) {
        return new WizzardPageFragment(layouts[pageNumber]);
    }

    View.OnClickListener fabClickListener = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (currentFragment > layouts.length - 1) {
                getSharedPreferences(getPackageName(), Context.MODE_PRIVATE).edit().putBoolean("first_launch", false).apply();
                startActivity(new Intent(WizzardActivity.this, AuthenticationActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            } else {
                fragmentManager.beginTransaction().setCustomAnimations(R.anim.anim_slide_in_left, R.anim.anim_slide_out_left, R.anim.anim_slide_in_left, R.anim.anim_slide_out_right)
                        .replace(R.id.wizzard_fragment_container, pager(currentFragment++)).commit();
                progressBar.setProgress((currentFragment * 100) / layouts.length);
            }
        }
    };
    int[] layouts = {R.layout.wizzard_page1, R.layout.wizzard_page2, R.layout.wizzard_page3};
    int currentFragment = 0;
    FragmentManager fragmentManager;
    FloatingActionButton fab;
    ProgressBar progressBar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_wizzard);
        fab = findViewById(R.id.wizzard_fab);
        fab.setOnClickListener(fabClickListener);
        progressBar = findViewById(R.id.wizzard_progressbar);
        fragmentManager = getSupportFragmentManager();
        fab.callOnClick();

    }
}

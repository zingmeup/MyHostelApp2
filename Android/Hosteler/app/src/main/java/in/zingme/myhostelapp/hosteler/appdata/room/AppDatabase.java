package in.zingme.myhostelapp.hosteler.appdata.room;

import android.arch.persistence.room.Database;
import android.arch.persistence.room.Room;
import android.arch.persistence.room.RoomDatabase;
import android.content.Context;

import in.zingme.myhostelapp.hosteler.appdata.datamodels.ActiveOuting;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.HistoryOuting;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.UserCard;

@Database(entities = {UserCard.class, ActiveOuting.class, HistoryOuting.class}, version = 2, exportSchema = false)
public abstract class AppDatabase extends RoomDatabase {
    static AppDatabase db;
    public abstract DaoAccess userCardDao();

    public static AppDatabase getInstance(Context context) {
        if (db == null) {
            db = Room.databaseBuilder(context,
                    AppDatabase.class, "database-name").build();
        }
        return db;
    }

}

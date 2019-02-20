package in.zingme.myhostelapp.hosteler.appdata.room;

import android.arch.persistence.room.Dao;
import android.arch.persistence.room.Delete;
import android.arch.persistence.room.Insert;
import android.arch.persistence.room.OnConflictStrategy;
import android.arch.persistence.room.Query;
import android.arch.persistence.room.Update;

import java.util.List;

import in.zingme.myhostelapp.hosteler.appdata.datamodels.ActiveOuting;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.HistoryOuting;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.UserCard;
@Dao
public interface DaoAccess {
    @Insert(onConflict = OnConflictStrategy.REPLACE)
    void insertUserCard(UserCard userCard);

    @Query("SELECT * from user_card LIMIT 1")
    public List<UserCard> loadUserCard();

    @Query("SELECT count(pass_id) FROM active_outing")
    public int getActiveOutingCount();

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    void insertActive(ActiveOuting activeOuting);

    @Update
    void updateActiveOuting(ActiveOuting activeOuting);

    @Query("SELECT * FROM active_outing")
    public List<ActiveOuting> getActiveOuting();

    @Delete
    public void deleteActiveOuting(ActiveOuting activeOuting);

    @Insert(onConflict = OnConflictStrategy.IGNORE)
    void insertOutingHistory(List<HistoryOuting> h);

    @Query("SELECT * FROM history_outing")
    public List<HistoryOuting> getHistoryOuting();


    @Query("DELETE FROM history_outing")
    public void deleteHistoryOuting();

    @Query("SELECT count(id) FROM history_outing")
    public int getHistoryOutingCount();
}

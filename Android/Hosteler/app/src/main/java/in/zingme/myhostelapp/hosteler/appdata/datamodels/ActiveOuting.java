package in.zingme.myhostelapp.hosteler.appdata.datamodels;


import android.arch.persistence.room.ColumnInfo;
import android.arch.persistence.room.Entity;
import android.arch.persistence.room.PrimaryKey;
import android.support.annotation.NonNull;

@Entity(tableName = "active_outing")
public class ActiveOuting {
    @NonNull
    @PrimaryKey
    @ColumnInfo(name = "pass_id")
    String passId;
    @ColumnInfo(name = "user_id")
    String userId;
    @ColumnInfo(name = "otp")
    String otp;
    @ColumnInfo(name = "hash")
    String hash;
    @ColumnInfo(name = "phone")
    String phone;
    @ColumnInfo(name = "place")
    String place;
    @ColumnInfo(name = "purpose")
    String purpose;
    @ColumnInfo(name = "going_with")
    String goingWith;
    @ColumnInfo(name = "hostel")
    String hostel;
    @ColumnInfo(name = "date_of_appl")
    String dateOfApply;
    @ColumnInfo(name = "time_of_appl")
    String timeOfApply;
    @ColumnInfo(name = "date_exp_out")
    String dateExpOut;
    @ColumnInfo(name = "date_exp_in")
    String dateExpIn;
    @ColumnInfo(name = "time_exp_out")
    String timeExpOut;
    @ColumnInfo(name = "parent_no")
    String parentNo;
    @ColumnInfo(name = "date_out")
    String dateOut;
    @ColumnInfo(name = "date_in")
    String dateIn;
    @ColumnInfo(name = "time_out")
    String timeOut;
    @ColumnInfo(name = "time_in")
    String time_in;
    @ColumnInfo(name = "warden_id")
    String wardenId;
    @ColumnInfo(name = "remark")
    String remark;
    @ColumnInfo(name = "sec_out_id")
    String secOutId;
    @ColumnInfo(name = "sec_in_id")
    String secInId;
    @ColumnInfo(name = "status")
    String status;
    @ColumnInfo(name = "type")
    String type;
    @ColumnInfo(name = "bonus")
    String bonus;

    @NonNull
    public String getPassId() {
        return passId;
    }

    public String getBonus() {
        return bonus;
    }

    public void setBonus(String bonus) {
        this.bonus = bonus;
    }

    public void setPassId(@NonNull String passId) {
        this.passId = passId;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }

    public String getOtp() {
        return otp;
    }

    public void setOtp(String otp) {
        this.otp = otp;
    }

    public String getHash() {
        return hash;
    }

    public void setHash(String hash) {
        this.hash = hash;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public String getPlace() {
        return place;
    }

    public void setPlace(String place) {
        this.place = place;
    }

    public String getPurpose() {
        return purpose;
    }

    public void setPurpose(String purpose) {
        this.purpose = purpose;
    }

    public String getGoingWith() {
        return goingWith;
    }

    public void setGoingWith(String goingWith) {
        this.goingWith = goingWith;
    }

    public String getHostel() {
        return hostel;
    }

    public void setHostel(String hostel) {
        this.hostel = hostel;
    }

    public String getDateOfApply() {
        return dateOfApply;
    }

    public void setDateOfApply(String dateOfApply) {
        this.dateOfApply = dateOfApply;
    }

    public String getTimeOfApply() {
        return timeOfApply;
    }

    public void setTimeOfApply(String timeOfApply) {
        this.timeOfApply = timeOfApply;
    }

    public String getDateExpOut() {
        return dateExpOut;
    }

    public void setDateExpOut(String dateExpOut) {
        this.dateExpOut = dateExpOut;
    }

    public String getDateExpIn() {
        return dateExpIn;
    }

    public void setDateExpIn(String dateExpIn) {
        this.dateExpIn = dateExpIn;
    }

    public String getTimeExpOut() {
        return timeExpOut;
    }

    public void setTimeExpOut(String timeExpOut) {
        this.timeExpOut = timeExpOut;
    }

    public String getParentNo() {
        return parentNo;
    }

    public void setParentNo(String parentNo) {
        this.parentNo = parentNo;
    }

    public String getDateOut() {
        return dateOut;
    }

    public void setDateOut(String dateOut) {
        this.dateOut = dateOut;
    }

    public String getDateIn() {
        return dateIn;
    }

    public void setDateIn(String dateIn) {
        this.dateIn = dateIn;
    }

    public String getTimeOut() {
        return timeOut;
    }

    public void setTimeOut(String timeOut) {
        this.timeOut = timeOut;
    }

    public String getTime_in() {
        return time_in;
    }

    public void setTime_in(String time_in) {
        this.time_in = time_in;
    }

    public String getWardenId() {
        return wardenId;
    }

    public void setWardenId(String wardenId) {
        this.wardenId = wardenId;
    }

    public String getRemark() {
        return remark;
    }

    public void setRemark(String remark) {
        this.remark = remark;
    }

    public String getSecOutId() {
        return secOutId;
    }

    public void setSecOutId(String secOutId) {
        this.secOutId = secOutId;
    }

    public String getSecInId() {
        return secInId;
    }

    public void setSecInId(String secInId) {
        this.secInId = secInId;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public ActiveOuting(@NonNull String passId, String userId, String otp, String hash, String phone, String place, String purpose, String goingWith, String hostel, String dateOfApply, String timeOfApply, String dateExpOut, String dateExpIn, String timeExpOut, String parentNo, String dateOut, String dateIn, String timeOut, String time_in, String wardenId, String remark, String secOutId, String secInId, String status, String type, String bonus) {
        this.passId = passId;
        this.userId = userId;
        this.otp = otp;
        this.hash = hash;
        this.phone = phone;
        this.place = place;
        this.purpose = purpose;
        this.goingWith = goingWith;
        this.hostel = hostel;
        this.dateOfApply = dateOfApply;
        this.timeOfApply = timeOfApply;
        this.dateExpOut = dateExpOut;
        this.dateExpIn = dateExpIn;
        this.timeExpOut = timeExpOut;
        this.parentNo = parentNo;
        this.dateOut = dateOut;
        this.dateIn = dateIn;
        this.timeOut = timeOut;
        this.time_in = time_in;
        this.wardenId = wardenId;
        this.remark = remark;
        this.secOutId = secOutId;
        this.secInId = secInId;
        this.status = status;
        this.type = type;
        this.bonus=bonus;
    }
}
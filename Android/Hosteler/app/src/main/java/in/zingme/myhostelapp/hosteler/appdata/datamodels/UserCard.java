package in.zingme.myhostelapp.hosteler.appdata.datamodels;

import android.arch.persistence.room.ColumnInfo;
import android.arch.persistence.room.Entity;
import android.arch.persistence.room.PrimaryKey;
import android.support.annotation.NonNull;


@Entity(tableName = "user_card")
public class UserCard {
    @NonNull
    @PrimaryKey
    @ColumnInfo(name = "user_id")
    String userId;
    @ColumnInfo(name = "access_token")
    String accessToken;
    @ColumnInfo(name = "type")
    String type;
    @ColumnInfo(name = "gender")
    String gender;
    @ColumnInfo(name = "branch")
    String branch;
    @ColumnInfo(name = "course")
    String course;
    @ColumnInfo(name = "section")
    String section;
    @ColumnInfo(name = "name")
    String name;
    @ColumnInfo(name = "hostel_id")
    String hostelId;
    @ColumnInfo(name = "hostel")
    String hostel;
    @ColumnInfo(name = "room")
    String rooom;
    @ColumnInfo(name = "phone")
    String phone;
    @ColumnInfo(name = "email")
    String email;
    @ColumnInfo(name = "img")
    String img;
    @ColumnInfo(name = "status")
    byte status;
    @ColumnInfo(name = "outing_type")
    String outingType;
    @ColumnInfo(name = "bonus")
    short bonus;

    public UserCard(String userId, String accessToken, String type, String gender, String branch, String course, String section, String name, String hostelId, String hostel, String rooom, String phone, String email, String img) {
        this.userId = userId;
        this.accessToken = accessToken;
        this.type = type;
        this.gender = gender;
        this.branch = branch;
        this.course = course;
        this.section = section;
        this.name = name;
        this.hostelId = hostelId;
        this.hostel = hostel;
        this.rooom = rooom;
        this.phone = phone;
        this.email = email;
        this.img = img;
        status=0;
        outingType=null;
        bonus=0;
    }

    public byte getStatus() {
        return status;
    }

    public void setStatus(byte status) {
        this.status = status;
    }

    public String getOutingType() {
        return outingType;
    }

    public void setOutingType(String outingType) {
        this.outingType = outingType;
    }

    public short getBonus() {
        return bonus;
    }

    public void setBonus(short bonus) {
        this.bonus = bonus;
    }

    @NonNull
    public String getUserId() {
        return userId;
    }

    public void setUserId(@NonNull String userId) {
        this.userId = userId;
    }

    public String getAccessToken() {
        return accessToken;
    }

    public void setAccessToken(String accessToken) {
        this.accessToken = accessToken;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getGender() {
        return gender;
    }

    public void setGender(String gender) {
        this.gender = gender;
    }

    public String getBranch() {
        return branch;
    }

    public void setBranch(String branch) {
        this.branch = branch;
    }

    public String getCourse() {
        return course;
    }

    public void setCourse(String course) {
        this.course = course;
    }

    public String getSection() {
        return section;
    }

    public void setSection(String section) {
        this.section = section;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getHostelId() {
        return hostelId;
    }

    public void setHostelId(String hostelId) {
        this.hostelId = hostelId;
    }

    public String getHostel() {
        return hostel;
    }

    public void setHostel(String hostel) {
        this.hostel = hostel;
    }

    public String getRooom() {
        return rooom;
    }

    public void setRooom(String rooom) {
        this.rooom = rooom;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getImg() {
        return img;
    }

    public void setImg(String img) {
        this.img = img;
    }
}

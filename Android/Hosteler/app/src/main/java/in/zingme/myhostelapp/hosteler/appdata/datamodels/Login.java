package in.zingme.myhostelapp.hosteler.appdata.datamodels;

public class Login {
    static String userId;
    static String pass;
    public static String getUserId() {
        return userId;
    }

    public static void setUserId(String userId) {
        Login.userId = userId;
    }

    public static String getPass() {
        return String.valueOf(pass);
    }

    public static void setPass(String pass) {
        Login.pass = pass;
    }
}

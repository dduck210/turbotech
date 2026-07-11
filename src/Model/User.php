<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * User account queries. Ported from `model/nguoidung.php`. `byEmail()` drops
 * the old inline echo on "not found" — callers decide how to present that.
 */
class User
{
    /**
     * Create a new user account, including delivery address/phone so
     * checkout can prefill them from the session immediately after login
     * (`view/cart/bill.php` already reads `$address`/`$phone_user` from
     * `$_SESSION['user']` — this is the other half of that link).
     * Mirrors old `register($user_name, $full_name, $email_user, $password)`,
     * extended with `$address`/`$phone_user`/`$sex` — `$sex` used to only be
     * settable from the account-settings page, silently defaulting to 0
     * ("Nam") for every new signup regardless of the account holder's actual
     * answer, since registration never asked.
     */
    public static function register(string $user_name, string $full_name, string $email_user, string $password, string $address = '', string $phone_user = '', int $sex = 0): void
    {
        $sql = "INSERT INTO user (user_name, full_name, email_user, password, address, phone_user, sex) VALUES (?, ?, ?, ?, ?, ?, ?)";
        Database::execute($sql, $user_name, $full_name, $email_user, $password, $address, $phone_user, $sex);
    }

    /**
     * Look up a user by username-or-email + password (login check).
     * Mirrors old `check_user($user_name, $password)`.
     *
     * @return array|false
     */
    public static function check(string $user_name, string $password)
    {
        $sql = "SELECT * FROM user WHERE ((user_name = ?) OR (email_user = ?)) AND password = ?";
        return Database::queryOne($sql, $user_name, $user_name, $password);
    }

    /**
     * Which of username/email/phone (if any) is already taken — the `user`
     * table has no unique constraint on any of the three, so without this
     * check register() would silently create duplicate accounts. Checked
     * one at a time (rather than a single OR query returning a bool) so the
     * caller can give a specific "your phone number is already registered"
     * message instead of a generic one.
     *
     * @return string|null 'phone', 'email', 'username', or null when none match.
     */
    public static function findDuplicateField(string $user_name, string $email_user, string $phone_user): ?string
    {
        if ($phone_user !== '' && Database::queryOne("SELECT 1 FROM user WHERE phone_user = ? LIMIT 1", $phone_user) !== false) {
            return 'phone';
        }
        if (Database::queryOne("SELECT 1 FROM user WHERE email_user = ? LIMIT 1", $email_user) !== false) {
            return 'email';
        }
        if (Database::queryOne("SELECT 1 FROM user WHERE user_name = ? LIMIT 1", $user_name) !== false) {
            return 'username';
        }

        return null;
    }

    /**
     * Look up a user by email OR phone number (forgot-password flow) — lets
     * someone recover their account with whichever detail they remember.
     * The reset code is still always sent to the account's email, since
     * that's the only delivery channel actually wired up (no SMS gateway).
     *
     * @return array|false
     */
    public static function findByEmailOrPhone(string $identifier)
    {
        $sql = "SELECT * FROM user WHERE email_user = ? OR phone_user = ?";
        return Database::queryOne($sql, $identifier, $identifier);
    }

    /**
     * Look up a user by email. Returns false when not found — the caller
     * decides how to report that (the old code echoed HTML here; dropped).
     * Mirrors old `getUserEmail($email)`.
     *
     * @return array|false
     */
    public static function byEmail(string $email)
    {
        $sql = "SELECT * FROM user where email_user = ?";
        return Database::queryOne($sql, $email);
    }

    /**
     * Update a user's password directly (account settings flow).
     * Mirrors old `updatePass($user_name, $password)`.
     */
    public static function updatePassword(string $user_name, string $password): void
    {
        $sql = "UPDATE `user` SET password = ? WHERE user_name = ?";
        Database::execute($sql, $password, $user_name);
    }

    /**
     * Reset a user's password by email (forgot-password flow).
     * Mirrors old `forgetPass($password, $email)`.
     */
    public static function resetPassword(string $password, string $email): void
    {
        $sql = "UPDATE user SET password = ? WHERE email_user = ?";
        Database::execute($sql, $password, $email);
    }

    /**
     * Update profile fields; conditionally updates the avatar image.
     * Mirrors old `update_user(...)`.
     */
    public static function update(int $id_user, string $img_user, string $full_name, int $sex, string $email_user, string $address, string $phone_user): void
    {
        if ($img_user != '') {
            $sql = "UPDATE user SET
    img_user = ?,
    full_name = ?,
    sex = ?,
    email_user = ?,
    address = ?,
    phone_user = ?
      WHERE id_user = ?";
            Database::execute($sql, $img_user, $full_name, $sex, $email_user, $address, $phone_user, $id_user);
        } else {
            $sql = "UPDATE user SET
        full_name = ?,
        sex = ?,
        email_user = ?,
        address = ?,
        phone_user = ?
          WHERE id_user = ?";
            Database::execute($sql, $full_name, $sex, $email_user, $address, $phone_user, $id_user);
        }
    }
}

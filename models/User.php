<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $password
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $fio
 * @property int|null $role_id
 *
 * @property Report[] $reports
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function __toString() {
        return (string) $this->login;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id'], 'integer'],
            [['login', 'password', 'email', 'fio'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['phone'], 'string', 'min'=> 11, 'max'=> 11],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'email' => 'Email',
            'phone' => 'Phone',
            'fio' => 'Fio',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[Reports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * Функция поиска пользователя по логину и паролю
     * @param string $login Логин пользователя
     * @param string $password Пароль пользователя
     * @return User|null Возвращает пользователя или null, если соответствующего пользователя нет
     */
    public static function login($login, $password) {
        // метод find() возвращает Query-объект (объект построения запроса в бд)
        // метод where([{column} => {value}]) добавляет условие и возвращает Query-объект (объект построения запроса в бд)
        // метод one() возвращает экземпляр соответствующего класса, либо null, если не найдено ни одной записи
        // Может быть заменено на метод findOne([{column} => {value}]), который является alias для find()->where([{column} => {value}])->one()
        // Происходит поиск пользователя по его логину
        $user = static::find()->where(['login' => $login])->one();

        // Проверка на пользователя и на совпадение его пароля
        if ($user && $user->validatePassword($password)) {
            return $user;
        }

        // Иначе возвращать null
        return null;
    }

    /**
     * Скопировано из User.php.dist
     * В будущем будет изменено для сравнения пароля по хешу
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

     /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        // Поиск пользователя по id. Может быть заменено на alias static::findOne(['id' => $id]);
        return static::find()->where(['id' => $id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return null;
    }
}
<?php
namespace App\Services;

use DB;
use Illuminate\Support\Facades\Hash;

// 継承元Serviceクラス
use App\Services\BaseService;

// 使用するModelクラス
use App\Models\User;

// データ取得用Trait
use App\Services\Traits\UserTrait;
use App\Services\Traits\StatusTrait;

class UserService extends BaseService
{
    use UserTrait;
    use StatusTrait;

    /**
     * EnableTraitでデータ取得するときのメソッド名
     *
     * @var string
     */
    protected $_enableTraitGetDataMethodName = 'getUser';

    /**
     * パスワード暗号化
     *
     * @param string $password
     * @return string
     */
    public function getHashPassword($password)
    {
        // パスワードは暗号化
        return Hash::make($password);
    }

    /**
     * 登録
     *
     * @param array $input
     * @return mixed
     */
    public function store(array $input)
    {
        $ret = DB::transaction(function () use ($input) {
            try {
                $input['password'] = $this->getHashPassword($input['password']);
                $id = User::create($input)->id;
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return $e;
            } catch (\Exception $e) {
                DB::rollBack();
                return $e;
            }
            return $id;
        });
        return $ret;
    }

    /**
     * 更新
     *
     * @param int $id
     * @param array $input
     * @param boolean $isTimestampUpdate 更新日時を更新しない場合はfalse
     * @return mixed
     */
    public function update($id, array $input, $isTimestampUpdate = true)
    {
        $id = DB::transaction(function () use ($id, $input, $isTimestampUpdate) {
            try {
                if (!empty($input['password'])) {
                    $input['password'] = $this->getHashPassword($input['password']);
                } else {
                    unset($input['password']);
                }
                $rowObj = $this->getUser($id);
                $rowObj->timestamps = !!$isTimestampUpdate;
                $rowObj->fill($input)->save();
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return $e;
            } catch (\Exception $e) {
                DB::rollBack();
                return $e;
            }
            return $id;
        });
        return $id;
    }

    /**
     * 最終ログイン日時更新
     *
     * @param int $id
     * @return mixed
     */
    public function updateLastLoginAt($id)
    {
        return $this->update($id, ['last_login_at' => date('Y-m-d H:i:s')], false);
    }

    /**
     * 一括追加
     *
     * @param  array $input
     * @param  string $date
     * @return mixed
     */
    public function importSave($input, $date)
    {
        $ret = DB::transaction(function () use ($input, $date) {
            try {
                $insertData = [];
                foreach ($input as $data) {
                    $data['password'] = $this->getHashPassword($data['password']);
                    $data['is_enabled'] = 1;
                    $data['created_at'] = $date;
                    $data['updated_at'] = $date;
                    $insertData[] = $data;
                }
                User::insert($insertData);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return $e;
            } catch (\Exception $e) {
                DB::rollBack();
                return $e;
            }
            return true;
        });
        return $ret;
    }
}

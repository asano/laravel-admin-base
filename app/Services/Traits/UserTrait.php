<?php
namespace App\Services\Traits;

use DB;

// 使用するModelクラス
use App\Models\User;

trait UserTrait
{
    /**
     * クエリビルダー取得
     *
     * @param  mixed $input  検索条件
     * @param  array $fields 取得するカラムを指定
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getUserQueryBuilder($input, array $fields = [], $limit = 10)
    {
        // 主テーブルを指定
        $pt = 'users';

        if (is_array($input)) {
            $input = collect($input);
        }

        $query = User::query();

        if ($fields) {
            $query->select($fields);
        }

        // 名前
        $field = 'name';
        if ($target = $input->get($field)) {
            $query->where("{$pt}.{$field}", 'like', '%'.$target.'%');
        }

        // メールアドレス
        $field = 'email';
        if ($target = $input->get($field)) {
            $query->where("{$pt}.{$field}", 'like', '%'.$target.'%');
        }

        // 状態
        $field = 'is_enabled';
        if (
            ($target = $input->get($field)) &&
            is_array($target) &&
            count($target)
        ) {
            $query->whereIn("{$pt}.{$field}", $target);
        }

        return $query;
    }

    /**
     * 一覧取得
     *
     * @param  mixed $input  検索条件
     * @param  array $fields 取得するカラムを指定
     * @param  int   $limit  取得件数
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUsers($input, array $fields = [], $limit = null)
    {
        if (is_null($limit)) {
            //// BaseServiceにあるメソッド
            //$siteConfigs = $this->getSiteFromCache($clientId);
            //$limit = $siteConfigs['page_limit'] ?? 10;
            $limit = 10;
        }
        return $this->getUserQueryBuilder($input, $fields)->paginate($limit);
    }

    /**
     * 詳細取得
     *
     * @param  int   $id     ID
     * @param  array $fields 取得するカラムを指定
     * @return App\Models\User
     */
    public function getUser($id, array $fields = ['*'])
    {
        return User::findOrFail($id, $fields);
    }
}

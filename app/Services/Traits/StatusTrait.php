<?php
namespace App\Services\Traits;

use DB;

trait StatusTrait
{
    // StatusTraitでデータを取るために、serviceのプロパティに
    // データ取得メソッド名を指定すること
    // protected $_statusTraitGetDataMethodName;

    /**
     * 有効化
     *
     * @param int $id
     * @return mixed
     */
    public function enable($id)
    {
        return $this->_changeIsEnabled($id, 1);
    }

    /**
     * 有効化
     *
     * @param array $ids
     * @return mixed
     */
    public function batchEnable($ids)
    {
        return $this->_changeBatchIsEnabled($ids, 1);
    }

    /**
     * 無効化
     *
     * @param int $id
     * @return mixed
     */
    public function disable($id)
    {
        return $this->_changeIsEnabled($id, 0);
    }

    /**
     * 無効化
     *
     * @param array $ids
     * @return mixed
     */
    public function batchDisable($ids)
    {
        return $this->_changeBatchIsEnabled($ids, 0);
    }

    /**
     * 有効・無効切り替え
     *
     * @param int $id
     * @param int $isEnabled
     * @return mixed
     */
    private function _changeIsEnabled($id, $isEnabled = 1)
    {
        return DB::transaction(function () use ($id, $isEnabled) {
            try {
                if ($row = $this->{$this->_enableTraitGetDataMethodName}($id)) {
                    $row->fill([
                        'is_enabled' => $isEnabled,
                    ])->save();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return $e;
            } catch (\Exception $e) {
                DB::rollBack();
                return $e;
            }
            return $id;
        });
    }

    /**
     * 有効・無効切り替え
     *
     * @param array $ids
     * @param int $isEnabled
     * @return mixed
     */
    private function _changeBatchIsEnabled($ids, $isEnabled = 1)
    {
        return DB::transaction(function () use ($ids, $isEnabled) {
            try {
                foreach ($ids as $id) {
                    if ($row = $this->{$this->_enableTraitGetDataMethodName}($id)) {
                        $row->fill([
                            'is_enabled' => $isEnabled,
                        ])->save();
                    }
                }
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return $e;
            } catch (\Exception $e) {
                DB::rollBack();
                return $e;
            }
            return array_pop($ids);
        });
    }
}

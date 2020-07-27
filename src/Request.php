<?php

namespace Sinofaneliu\LaravelStart;

use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;
use Sinofaneliu\LaravelStart\Requests\Traits\AnalysisRouteAction;

class Request extends FormRequest
{
    use AnalysisRouteAction;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * 根据请求方法获取不同的验证规则
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * 获取验证错误的自定义属性。
     *
     * @return array
     */
    public function attributes()
    {
        return [
            // 'email' => 'email address',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            // 'title.required' => 'A title is required',
            // 'body.required'  => 'A message is required',
        ];
    }

    public function updateRules($uniqueFields = [])
    {
        $rules = $this->rules;

        if (!$uniqueFields && !method_exists($this, 'uniqueFields')) {
            return $rules;
        }
        $fields = $uniqueFields ?: $this->uniqueFields();
        $model = $this->route(Str::snake($this->modelName));
        $id = is_numeric($model) ? $model : $model->id;

        foreach ($fields as $field) {
            $valids = $rules[$field];
            
            if (!is_array($valids)) {
                $valids = explode('|', $valids);
            }
            foreach ($valids as $k => $valid) {
                if (
                    is_string($valid) && 
                    Str::startsWith(strtolower($valid), 'unique')
                ) {

                    $arr = explode(',', substr($valid, 7));
                    $arr[1] = $arr[1] ?? $field;
                    $arr[2] = $id;
                    $valids[$k] = substr($valid, 0, 7).implode(',', $arr);
                    break;
                }

                if ($valid instanceof Unique) {
                    $valids[$k] = $valid->ignore($id);
                    break;
                }
            }
            $rules[$field] = $valids;
        }

        return $rules;
    }

    /**
     * 请求的默认数据。
     *
     * @return array
     */
    public function getDefaultValue()
    {
        return [];
    }

    /**
     * 获取通过验证数据并追加默认数据，
     * 用于新建模型时不再提示doesn't have a default value。
     *
     * @return array
     */
    public function validatedWithDefaults()
    {
        $data = $this->validator->validated();
        if (method_exists($this, 'getDefaultValue')) {
            $data = array_merge($this->getDefaultValue(), $data);
        }

        return $data;
    }

    /**
     * 配置验证器实例。
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $errors = $validator->errors();
            if ($errors->all()) {
                return apiFail(
                    $validator->errors()->first(),
                    $errors->all()
                );
            };
        });
    }
}

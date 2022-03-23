<?php

namespace App\Fresns\Panel\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plugin;
use App\Models\Language;
use App\Models\PluginUsage;
use App\Models\Role;

class ExpandManageController extends Controller
{
    public function index()
    {
        $plugins = Plugin::all();

        $plugins = $plugins->filter(function ($plugin) {
            return in_array('expandManage', $plugin->scene ?: []);
        });

        $pluginUsages = PluginUsage::where('type', 5)
            ->orderBy('rank_num')
            ->with('plugin', 'names')
            ->paginate();

        $roles = Role::with('names')->get();

        return view('FsView::expands.manage', compact('pluginUsages', 'plugins', 'roles'));
    }

    public function store(Request $request)
    {
        $pluginUsage = new PluginUsage;
        $pluginUsage->type = 5;
        $pluginUsage->name = $request->names[$this->defaultLanguage] ?? (current(array_filter($request->names)) ?: '');
        $pluginUsage->plugin_unikey = $request->plugin_unikey;
        $pluginUsage->parameter = $request->parameter;
        $pluginUsage->is_enable = $request->is_enable;
        $pluginUsage->rank_num = $request->rank_num;
        $pluginUsage->is_group_admin = $request->is_group_admin;
        $pluginUsage->roles = ($request->is_group_admin == 0 && $request->roles) ? implode(',', $request->roles) : '';
        $pluginUsage->scene = $request->scene ? implode(',', $request->scene) : '';
        $pluginUsage->icon_file_url = $request->icon_file_url;
        $pluginUsage->can_delete = 1;
        $pluginUsage->save();

        if ($request->update_name) {
            foreach ($request->names as $langTag => $content) {
                $language = Language::tableName('plugin_usages')
                    ->where('table_id', $pluginUsage->id)
                    ->where('lang_tag', $langTag)
                    ->first();

                if (!$language) {
                    // create but no content
                    if (!$content) {
                        continue;
                    }
                    $language = new Language();
                    $language->fill([
                        'table_name' => 'plugin_usages',
                        'table_column' => 'name',
                        'table_id' => $pluginUsage->id,
                        'lang_tag' => $langTag,
                    ]);
                }

                $language->lang_content = $content;
                $language->save();
            }
        }


        return $this->createSuccess();
    }

    public function update($id, Request $request)
    {
        $pluginUsage = PluginUsage::findOrFail($id);

        $pluginUsage->name = $request->names[$this->defaultLanguage] ?? (current(array_filter($request->names)) ?: '');
        $pluginUsage->plugin_unikey = $request->plugin_unikey;
        $pluginUsage->parameter = $request->parameter;
        $pluginUsage->is_enable = $request->is_enable;
        $pluginUsage->rank_num = $request->rank_num;
        $pluginUsage->is_group_admin = $request->is_group_admin;
        $pluginUsage->roles = ($request->is_group_admin == 0 && $request->roles) ? implode(',', $request->roles) : '';
        $pluginUsage->scene = $request->scene ? implode(',', $request->scene) : '';
        $pluginUsage->icon_file_url = $request->icon_file_url;
        $pluginUsage->save();

        if ($request->update_name) {
            foreach ($request->names as $langTag => $content) {
                $language = Language::tableName('plugin_usages')
                    ->where('table_id', $pluginUsage->id)
                    ->where('lang_tag', $langTag)
                    ->first();

                if (!$language) {
                    // create but no content
                    if (!$content) {
                        continue;
                    }
                    $language = new Language();
                    $language->fill([
                        'table_name' => 'plugin_usages',
                        'table_column' => 'name',
                        'table_id' => $pluginUsage->id,
                        'lang_tag' => $langTag,
                    ]);
                }

                $language->lang_content = $content;
                $language->save();
            }
        }

        return $this->updateSuccess();
    }

    public function destroy($id)
    {
        $pluginUsage = PluginUsage::findOrFail($id);
        $pluginUsage->delete();

        return $this->deleteSuccess();
    }

    public function updateRank($id, Request $request)
    {
        $pluginUsage = PluginUsage::findOrFail($id);
        $pluginUsage->rank_num = $request->rank_num;
        $pluginUsage->save();

        return $this->updateSuccess();
    }
}

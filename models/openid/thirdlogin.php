<?php


if (! defined('IN_ANWSION'))
{
    die;
}

class openid_weixin_thirdlogin_class extends AWS_MODEL
{
    
    public function get_third_party_login_by_account_id($account_id, $enabled = null, $order = null)
    {
        if (!is_digits($account_id))
        {
            return false;
        }

        $where[] = 'account_id = ' . $account_id;

        if ($enabled === true)
        {
            $where[] = 'enabled = 1';
        }
        else if ($enabled === false)
        {
            $where[] = 'enabled = 0';
        }

        return $this->fetch_all('weixin_third_party_login', implode(' AND ', $where), $order);
    }

    public function get_third_party_login_by_id($id)
    {
        if (!is_digits($id))
        {
            return false;
        }

        return $this->fetch_row('weixin_third_party_login', 'id = ' . $id);
    }
    
    public function get_third_party_login_by_name($name)
    {
        return $this->fetch_row('weixin_third_party_login', 'name = "'.$this->quote($name).'"');
    }

    public function remove_third_party_login_by_account_id($account_id)
    {
        if (!is_digits($account_id))
        {
            return false;
        }

        return $this->delete('weixin_third_party_login', 'account_id = ' . $account_id);
    }

    public function remove_third_party_login_by_id($id)
    {
        if (!is_digits($id))
        {
            return false;
        }

        return $this->delete('weixin_third_party_login', 'id = ' . $id);
    }

    public function update_third_party_login($id = null, $action, $name, $url, $token, $enabled = null, $account_id = null, $rank = null)
    {
        if ($action == 'update' AND !is_digits($id) OR $action == 'add' AND !is_digits($account_id))
        {
            return false;
        }

        $to_save_rule = array();
        
        if ($name)
        {
            $to_save_rule['name'] = $name;
        }
        
        if ($url)
        {
            $to_save_rule['url'] = $url;
        }

        if ($token)
        {
            $to_save_rule['token'] = $token;
        }

        if ($enabled !== null)
        {
            if ($enabled == 1)
            {
                $to_save_rule['enabled'] = '1';
            }
            else
            {
                $to_save_rule['enabled'] = '0';
            }
        }

        if (is_digits($account_id))
        {
            $to_save_rule['account_id'] = $account_id;
        }

        if (is_digits($rank) AND $rank >= 0 AND $rank <= 99)
        {
            $to_save_rule['rank'] = $rank;
        }

        switch ($action)
        {
            case 'add':
                return $this->insert('weixin_third_party_login', $to_save_rule);

                break;

            case 'update':
                return $this->update('weixin_third_party_login', $to_save_rule, 'id = ' . $id);

                break;

            default:
                return false;

                break;
        }
    }
}

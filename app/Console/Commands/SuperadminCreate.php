<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Hash;

class SuperadminCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superadmin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create superadmin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $details = $this->getDetails();
        
        $admin = $this->createSuperAdmin($details);
        
        $this->display($admin);
    }
    
        /**
     * Ask for admin details.
     *
     * @return array
     */
    private function getDetails() : array
    {
        $details['name'] = $this->ask('What is superadmin name?');
        $details['email'] = $this->ask('What is superadmin email?');
        $details['password'] = $this->secret('Enter password (at least 6 ch.)');
        $details['confirm_password'] = $this->secret('Confirm password:');
        while (! $this->isValidPassword($details['password'], $details['confirm_password'])) {
            if (! $this->isRequiredLength($details['password'])) {
                $this->error('Password lenght must be at least 6 chars');
            }
            if (! $this->isMatch($details['password'], $details['confirm_password'])) {
                $this->error('Password and confirm do not match.');
            }
            $details['password'] = $this->secret('Enter password (at least 6 ch.)');
            $details['confirm_password'] = $this->secret('Confirm password');
        }
        return $details;
    }
    
    /**
     * Create superadmin user.
     *
     * @param array $details
     * @return array
     */
    public function createSuperAdmin(array $details) : User
    {
        $details['password'] = Hash::make($details['password']);
        
        $user = new User($details);
        $user->is_super_admin = 1;
        $user->save();
        return $user;
    }
    
    /**
     * Display created admin.
     *
     * @param array $admin
     * @return void
     */
    private function display(User $admin) : void
    {
        $headers = ['Name', 'Email', 'SuperAdmin'];
        $fields = [
            'Name' => $admin->name,
            'email' => $admin->email,
            'admin' => $admin->isSuperAdmin()
        ];
        $this->info('SuperAdmin created');
        $this->table($headers, [$fields]);
    }
    
    /**
     * Check if password is vailid
     *
     * @param string $password
     * @param string $confirmPassword
     * @return boolean
     */
    private function isValidPassword(string $password, string $confirmPassword) : bool
    {
        return $this->isRequiredLength($password) &&
        $this->isMatch($password, $confirmPassword);
    }
    /**
     * Check if password and confirm password matches.
     *
     * @param string $password
     * @param string $confirmPassword
     * @return bool
     */
    private function isMatch(string $password, string $confirmPassword) : bool
    {
        return $password === $confirmPassword;
    }
    /**
     * Checks if password is longer than six characters.
     *
     * @param string $password
     * @return bool
     */
    private function isRequiredLength(string $password) : bool
    {
        return strlen($password) >= 6;
    }
}

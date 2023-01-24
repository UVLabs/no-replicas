# No Replicas!

### What it does

Prevents backup and file manager plugins from being activated on a WordPress website.

### Why

Users kept cloning the demo sites I created for [Location Picker at Checkout](https://lpacwp.com) and [Delivery & Pickup Scheduling](https://dpswp.com). Manually deactivating the demo license from those sites was a chore.

### How to use

Simply copy the `no-replicas.php` file into your `wp-content\plugins\mu-plugins` folder of your demo template. Create the `mu-plugins` folder if it does not exist.

### Misc

Feel free to submit a pull request with additional backup/cloning and file manager plugins. Feel free to fork and make the lib into a general purpose one.

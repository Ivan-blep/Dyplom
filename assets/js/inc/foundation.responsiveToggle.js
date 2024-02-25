import {ResponsiveToggle} from "foundation-sites/js/foundation.responsiveToggle";
import { MediaQuery } from 'foundation-sites/js/foundation.util.mediaQuery';
import { Motion } from 'foundation-sites/js/foundation.util.motion';

class CustomResponsiveToggle extends ResponsiveToggle {
	toggleMenu() {
		if (!MediaQuery.atLeast(this.options.hideFor)) {
			/**
			 * Fires when the element attached to the tab bar toggles.
			 * @event ResponsiveToggle#toggled
			 */
			if(this.options.animate) {   
				if (this.$targetMenu.is(':hidden')) {
					Motion.animateIn(this.$targetMenu, this.animationIn, () => {
						this.$element.trigger('toggled.zf.responsiveToggle');
						this.$targetMenu.find('[data-mutate]').triggerHandler('mutateme.zf.trigger');
					});
				}
				else {
					Motion.animateOut(this.$targetMenu, this.animationOut, () => {
						this.$element.trigger('toggled.zf.responsiveToggle');
					});
				}
			}
			else {
				this.$targetMenu.slideToggle(200);
				this.$targetMenu.find('[data-mutate]').trigger('mutateme.zf.trigger');
				this.$element.trigger('toggled.zf.responsiveToggle');
			}
		}
	}
}

export {CustomResponsiveToggle};
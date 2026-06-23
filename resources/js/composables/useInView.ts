import { onBeforeUnmount, onMounted, ref, type Ref } from 'vue';

interface UseInViewOptions {
    /** Fraction of the element that must be visible to trigger (0–1). */
    threshold?: number;
    /** Margin around the root, e.g. to trigger slightly before entering. */
    rootMargin?: string;
    /** Reveal only once, then stop observing. */
    once?: boolean;
}

interface UseInViewReturn {
    target: Ref<HTMLElement | null>;
    isVisible: Ref<boolean>;
}

/**
 * Observe a single element and flip `isVisible` when it scrolls into view.
 * Used for scroll-reveal animations across the landing page.
 */
export function useInView(options: UseInViewOptions = {}): UseInViewReturn {
    const {
        threshold = 0.15,
        rootMargin = '0px 0px -10% 0px',
        once = true,
    } = options;

    const target = ref<HTMLElement | null>(null);
    const isVisible = ref(false);
    let observer: IntersectionObserver | null = null;

    onMounted(() => {
        if (!target.value || typeof IntersectionObserver === 'undefined') {
            // No observer support: show the content rather than hiding it.
            isVisible.value = true;
            return;
        }

        observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting) {
                        isVisible.value = true;
                        if (once) {
                            observer?.disconnect();
                        }
                    } else if (!once) {
                        isVisible.value = false;
                    }
                }
            },
            { threshold, rootMargin },
        );

        observer.observe(target.value);
    });

    onBeforeUnmount(() => observer?.disconnect());

    return { target, isVisible };
}
